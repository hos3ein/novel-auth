<?php

namespace Hos3ein\NovelAuth;

use Hos3ein\NovelAuth\Features\Constants;
use Hos3ein\NovelAuth\Responses\LogoutResponse;
use Hos3ein\NovelAuth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class NovelAuthServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/novel-auth.php', 'novel-auth');

        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);

    }

    public function boot()
    {
        $this->configurePublishing();
        $this->configureRoutes();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'novel-auth');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'novel-auth');
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../stubs/novel-auth.php' => config_path('novel-auth.php'),
            ], 'novel-auth-config');

            $this->publishes([
                // __DIR__ . '/../stubs/AccountManager.php' => app_path('Actions/NovelAuth/AccountManager.php'),
                __DIR__ . '/../stubs/OtpManager.php' => app_path('Actions/NovelAuth/OtpManager.php'),
                __DIR__ . '/../stubs/NovelAuthServiceProvider.php' => app_path('Providers/NovelAuthServiceProvider.php'),
            ], 'novel-auth-support');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'novel-auth-migrations');
        }
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        if (NovelAuth::$registersRoutes) {
            Route::group([
                'namespace' => 'Hos3ein\NovelAuth\Http\Controllers',
                'domain' => config(Constants::$configDomain),
                'prefix' => config(Constants::$configPrefix),
            ], function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
            });
        }
    }
}
