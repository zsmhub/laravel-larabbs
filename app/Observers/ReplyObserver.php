<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        // 评论数字段赋值
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
    }

    public function saving(Reply $reply)
    {
        // xss 过滤，这里有个问题待处理：content 字段可能会被处理为空且被保存进数据库！ -- bug
        $reply->content = clean($reply->content);
        $reply->save();
    }
}
