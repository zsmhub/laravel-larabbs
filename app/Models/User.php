<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use MustVerifyEmailTrait;

    use Notifiable {
        notify as protected laravelNotify;
    }

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 访问器-头像链接字段
     * @param string $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        if (empty($value)) {
            return 'https://cdn.learnku.com/uploads/images/201709/20/1/PtDKbASVcz.png?imageView2/1/w/600/h/60';
        }
        return config('app.url') . $value;
    }

    public function setAvatarAttribute($path)
    {
        // 后台传输默认链接头像时
        if (starts_with($path, 'http')) {
            return null;
        }

        // 如果不是 `/uploads` 子串开头，那就是从后台上传的，需要补全 URL
        if (!starts_with($path, '/uploads')) {

            // 拼接完整的 URL
            $path = "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

    // 修改器
    public function setPasswordAttribute($value)
    {
        // 长度不等于60，做密码加密处理，方便 Administrator 后台更改密码
        if (strlen($value) != 60) {
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 消息通知重构
    public function notify ($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有数据库类型通知蔡旭提醒，直接发送 Email 或者其它的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    // 标记通知消息为已读，未读数量清零
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
