<?php

namespace FelipeTrindade8\LaravelDiskMonitor\Http\Controllers;

use FelipeTrindade8\LaravelDiskMonitor\Models\DiskMonitorEntry;

class DiskMetricsController
{
    public function __invoke()
    {
        $entries = DiskMonitorEntry::orderByDesc('created_at')->get();

        return view('laravel-disk-monitor::entries', compact('entries'));
    }
}
