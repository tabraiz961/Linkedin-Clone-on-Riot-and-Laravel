<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewData extends ServiceProvider
{
    public function __construct() {
        die('asdasd');
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    
    {
        echo "abcd";
    die('calfdl');
            View::composer('view', function () {
    });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
