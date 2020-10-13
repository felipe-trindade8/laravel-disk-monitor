<?php

namespace FelipeTrindade8\LaravelDiskMonitor;

use FelipeTrindade8\LaravelDiskMonitor\Commands\LaravelDiskMonitorCommand;
use FelipeTrindade8\LaravelDiskMonitor\Http\Controllers\DiskMetricsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelDiskMonitorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishables()
            ->registerCommands()
            ->registerRoutes()
            ->registerViews();
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

    protected function registerPublishables(): self
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
        }

        return $this;
    }

    protected function registerCommands(): self
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LaravelDiskMonitorCommand::class,
            ]);
        }

        return $this;
    }

    protected function registerRoutes(): self
    {
        Route::macro('diskMonitor', function (string $prefix) {
            Route::prefix($prefix)->group(function () {
                Route::get('/', DiskMetricsController::class);
            });
        });

        return $this;
    }

    protected function registerViews(): self
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-disk-monitor');

        return $this;
    }
}
