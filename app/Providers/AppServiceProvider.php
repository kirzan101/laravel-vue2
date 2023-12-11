<?php

namespace App\Providers;

use App\Interfaces\UserGroupInterface;
use App\Interfaces\UserGroupPermissionInterface;
use App\Services\UserGroupPermissionService;
use App\Services\UserGroupService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserGroupInterface::class, UserGroupService::class);
        $this->app->bind(UserGroupPermissionInterface::class, UserGroupPermissionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
