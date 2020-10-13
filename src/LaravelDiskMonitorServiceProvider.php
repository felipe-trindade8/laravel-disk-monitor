<?php

namespace Felipe-trindade8\LaravelDiskMonitor;

use Illuminate\Support\ServiceProvider;
use Felipe-trindade8\LaravelDiskMonitor\Commands\LaravelDiskMonitorCommand;

class LaravelDiskMonitorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-disk-monitor.php' => config_path('laravel-disk-monitor.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-disk-monitor'),
            ], 'views');

            $migrationFileName = 'create_laravel_disk_monitor_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                LaravelDiskMonitorCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-disk-monitor');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-disk-monitor.php', 'laravel-disk-monitor');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
