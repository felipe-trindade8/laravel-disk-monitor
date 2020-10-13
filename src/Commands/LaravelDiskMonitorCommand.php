<?php

namespace FelipeTrindade8\LaravelDiskMonitor\Commands;

use FelipeTrindade8\LaravelDiskMonitor\Models\DiskMonitorEntry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LaravelDiskMonitorCommand extends Command
{
    public $signature = 'laravel-disk-monitor:record-metrics';

    public $description = 'Record the metrics of a disk';

    public function handle()
    {
        $this->comment('Recording metrics...');

        $disk_name = config('laravel-disk-monitor.disk_name');

        $file_count = count(Storage::disk($disk_name)->allFiles());

        DiskMonitorEntry::create([
            'disk_name' => $disk_name,
            'file_count' => $file_count,
        ]);

        $this->comment('All done!');
    }
}
