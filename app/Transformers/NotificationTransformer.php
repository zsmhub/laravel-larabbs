<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Notifications\DatabaseNotification;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $notification)
    {
        return [
            'id' => $notification->id,
            'type' => $notification->type,
            'data' => $notification->data,
            'read_at' => $notification->read_at ? $notification->read_at->toDateTimesString() : null,
            'created_at' => $notification->created_at->toDateTimesString(),
            'updated_at' => $notification->updated_at->toDateTimeString(),
        ];
    }
}
