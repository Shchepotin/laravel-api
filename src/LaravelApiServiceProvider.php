<?php

namespace Schepotin\LaravelApi;

use Illuminate\Support\ServiceProvider;

class LaravelApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\RunCommand::class,
            ]);
        }
    }
}