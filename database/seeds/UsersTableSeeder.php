<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成数据集合
        $users = factory(User::class)->times(10)->make();

        // 让隐藏字段可见，并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'Michael';
        $user->email = 'michael@example.com';
        $user->save();

        // 初始化用户角色，将 1 号用户指派为「站长」
        $user->assignRole('Founder');

        // 将 2 号用户指派为『管理员』
        $user_two = User::find(2);
        $user_two->assignRole('Maintainer');

        // 将 3 号用户指派为『管理员』
        $user_three = User::find(3);
        $user_three->assignRole('Maintainer');
    }
}
