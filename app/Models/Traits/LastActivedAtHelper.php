<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    protected $hash_prefix = 'larbbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        $date = Carbon::now()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString($date);

        // 字段名称，如：user_1
        $field = $this->getHashField();

        $now = Carbon::now()->toDateTimeString();

        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // $yesterday_date = Carbon::yesterday()->toDateString();  // 临时注释
        $yesterday_date = Carbon::now()->toDateString();
        $hash = $this->getHashFromDateString($yesterday_date);
        $dates = Redis::hGetAll($hash);
        foreach ($dates as $user_id => $actived_at) {
            $user_id = str_replace($this->field_prefix, '', $user_id);
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        $date = Carbon::now()->toDateString();
        $hash = $this->getHashFromDateString($date);
        $field = $this->getHashField();
        $datetime = Redis::hGet($hash, $field) ?: $value;
        if ($datetime) {
            return new Carbon($datetime);
        } else {
            return $this->created_at;
        }
    }

    public function getHashFromDateString($date)
    {
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        return $this->field_prefix . $this->id;
    }
}
