<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Models\Reply;
use App\Models\User;
use App\Transformers\ReplyTransformer;
use App\Http\Requests\Api\ReplyRequest;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic()->associate($topic);  // 设置外键关联
        $reply->user()->associate($this->user());
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Topic $topic, Reply $reply)
    {
        if ($reply->topic_id != $topic->id) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }

    public function index(Topic $topic, Request $request)
    {
        /* $replies = $topic->replies()->paginate(20);
        return $this->response->paginator($replies, new ReplyTransformer()); */

        // 关闭 Dingo 的预加载，手动处理预加载的问题。
        app(\Dingo\Api\Transformer\Factory::class)->disableEagerLoading();

        $replies = $topic->replies()->paginate(20);

        if ($request->include) {
            $replies->load(explode(',', $request->include));
        }

        return $this->response->paginator($replies, new ReplyTransformer());
    }

    public function userIndex(User $user, Request $request)
    {
        /* $replies = $user->replies()->paginate(20);
        return $this->response->paginator($replies, new ReplyTransformer()); */

        // 关闭 Dingo 的预加载，手动处理预加载的问题。
        app(\Dingo\Api\Transformer\Factory::class)->disableEagerLoading();

        $replies = $user->replies()->paginate(20);

        if ($request->include) {
            $replies->load(explode(',', $request->include));
        }

        return $this->response->paginator($replies, new ReplyTransformer());
    }
}
