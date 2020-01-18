<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }

        // API 的异常处理，防止暴露代码细节
        \API::error(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
            // throw new \Symfony\component\HttpKernel\Exception\HttpException(404, '404 Not Found');
            abort(404);
        });

        // API 的权限控制状态码控制
        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);
		\App\Models\Link::observe(\App\Observers\LinkObserver::class);

        //
    }
}
