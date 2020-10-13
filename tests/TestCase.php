<?php

namespace FelipeTrindade8\LaravelDiskMonitor\Tests;

use FelipeTrindade8\LaravelDiskMonitor\LaravelDiskMonitorServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Route::diskMonitor('disk-monitor');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDiskMonitorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);


        include_once __DIR__ . '/../database/migrations/create_laravel_disk_monitor_table.php.stub';
        (new \CreateLaravelDiskMonitorTable())->up();
    }
}
