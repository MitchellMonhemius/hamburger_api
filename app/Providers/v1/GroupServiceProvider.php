<?php

namespace App\Providers\v1;

use App\Services\v1;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class GroupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GroupService::class, function($app) {
            return new GroupService();
        });
    }
}
