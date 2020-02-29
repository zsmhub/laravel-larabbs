<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function socialStore($type, SocialAuthorizationRequest $request) {
        if (!in_array($type, ['weixin'])) {
            return $this->response->errorBadRequest();
        }

        $driver = \Socialite::driver($type);

        // 获取第三方登录平台的用户信息
        try {
            if ($code = $request->code) {
                $response = $driver->getAccessTokenResponse($code);
                $token = Arr::get($response, 'access_token');  // 这个方法会自动设置 openid 属性
            } else {
                $token = $request->access_token;

                if ($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);
        } catch (\Excepition $e) {
            return $this->response->errorUnauthorized('参数错误，为获取用户信息');
        }

        switch ($type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                // 没有找到用户，默认创建一个用户
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }

                break;
        }

        $token = \Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token);
    }

    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            // return $this->response->errorUnauthorized('用户名或密码错误');
            return $this->response->errorUnauthorized(trans('auth.failed'));
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            // 'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            'expires_in' => \Auth::guard('api')->factory()->getTTL()
        ]);
    }

    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }
}
