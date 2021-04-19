<?php

namespace LaravelWorkcast;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/workcast.php' => config_path('workcast.php'),
            ], 'config');


            $this->commands([
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/workcast.php', 'workcast');

        $this->app->bind(WorkcastApi::class, function ($app) {
            return new WorkcastApi(config('workcast.api_key'));
        });
    }
}
