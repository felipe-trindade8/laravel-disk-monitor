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

        collect(config('laravel-disk-monitor.disk_names'))
            ->each(fn (string $disk_name) => $this->recordsMetrics($disk_name));

        $this->comment('All done!');
    }

    protected function recordsMetrics(string $disk_name): void
    {
        $this->info('Recording metrics for disk `{$disk_name}`...');

        $disk = Storage::disk($disk_name);
        $file_count = count($disk->allFiles());

        DiskMonitorEntry::create([
            'disk_name' => $disk_name,
            'file_count' => $file_count,
        ]);
    }
}
