<?php

namespace Square1\ChartGenerator;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $source = dirname(__DIR__).'/config/chart-generator.php';

        $this->mergeConfigFrom($source, 'chart-generator');

        $this->publishes([
            dirname(__DIR__).'/config/chart-generator.php' => config_path('chart-generator.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
    }
}
