<?php

namespace App\Providers;

//namespace App\Authentication;

use Auth;
use App\Authentication\UserProvider;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Auth::provider('scds-t', function($app, array $config) {
            return new UserProvider();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
