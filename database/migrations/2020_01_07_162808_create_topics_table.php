<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTopicsTable extends Migration
{
	public function up()
	{
		Schema::create('topics', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index()->comment('帖子标题');
            $table->text('body')->comment('帖子内容');
            $table->bigInteger('user_id')->unsigned()->index()->comment('用户ID');
            $table->integer('category_id')->unsigned()->index()->comment('分类ID');
            $table->integer('reply_count')->unsigned()->default(0)->comment('回复数量');
            $table->integer('view_count')->unsigned()->default(0)->comment('查看总数');
            $table->integer('last_reply_user_id')->unsigned()->default(0)->comment('最后回复的用户ID');
            $table->integer('order')->unsigned()->default(0)->comment('排序');
            $table->text('excerpt')->nullable()->comment('文章摘要');
            $table->string('slug')->nullable()->comment('SEO 友好的 URI');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `topics` comment '帖子'");
	}

	public function down()
	{
		Schema::drop('topics');
	}
}
