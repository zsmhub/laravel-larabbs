<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecentReplied($query)
    {
        // 按话题最后更新时间排序
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按话题最新发布时间排序
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }

        // 预加载防止 N+1 问题
        return $query->with('user', 'category');
    }

    // topics.show 路由的URL拼接
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 设置评论数
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }
}
