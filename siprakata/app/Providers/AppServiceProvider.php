<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) return;

        $req = request();
        $base = $req->getSchemeAndHttpHost() . $req->getBasePath();
        URL::forceRootUrl($base);
    }
}
