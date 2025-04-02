<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
   
    public function register()
    {
        //
    }

    public function boot()
    {
        /* Auth::viaRequest('null-user', function () {
        return new App\Models\User(); // Returning a blank or default user instance.
    });*/
        // Schema::defaultStringLength(191);
    }
}

/*
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Correctly import the User model

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Auth::viaRequest('null-user', function () {
            return new User(); // Correct reference to the User model
        });
    }
}
*/