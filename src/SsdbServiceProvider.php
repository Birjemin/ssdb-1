<?php

namespace Haolyy\Ssdb;

use Cache;
use Illuminate\Support\ServiceProvider;

class SsdbServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Cache::extend('ssdb', function($app) {
            return Cache::repository(new Ssdb($app));
        });
    }
}