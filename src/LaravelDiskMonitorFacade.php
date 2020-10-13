<?php

namespace Felipe-trindade8\LaravelDiskMonitor;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Felipe-trindade8\LaravelDiskMonitor\LaravelDiskMonitor
 */
class LaravelDiskMonitorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-disk-monitor';
    }
}
