<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PagesController extends Controller
{
    public function root()
    {
        return view('pages.root');
    }

    // 后台访问失败时，使用自定义视图展示
    public function permissionDenied()
    {
        // 如果当前用户有权限访问后台，直接跳转访问
        if (config('administrator.permission')()) {
            return redirect(url(config('administrator.uri')), 302);
        }

        // 否则时候用视图
        return view('pages.permission_denied');
    }
}
