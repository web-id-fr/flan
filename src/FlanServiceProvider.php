<?php

namespace WebId\Flan;

use Illuminate\Support\ServiceProvider;
use WebId\Flan\Commands\FilterCreate;
use WebId\Flan\Commands\MakeFilterClass;
use WebId\Flan\Commands\MakeFilterConfig;

class FlanServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations/filters');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
            $this->commands([
                MakeFilterClass::class,
                MakeFilterConfig::class,
                FilterCreate::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/flan.php', 'flan');

        // Register the service the package provides.
        $this->app->singleton('flan', function ($app) {
            return new Flan;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return ['flan'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/flan.php' => config_path('flan.php'),
        ], 'flan.config');

        $this->publishes([
            __DIR__.'/Database/migrations/create_filters_tables.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_filters_tables.php'),
        ], 'flan.migrations');

        $this->loadRoutesFrom(__DIR__.'/Routes/json.php');
    }
}
