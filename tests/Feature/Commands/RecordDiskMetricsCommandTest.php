<?php

namespace FelipeTrindade8\LaravelDiskMonitor\Tests\Feature\Commands;

use FelipeTrindade8\LaravelDiskMonitor\Commands\LaravelDiskMonitorCommand;
use FelipeTrindade8\LaravelDiskMonitor\Models\DiskMonitorEntry;
use FelipeTrindade8\LaravelDiskMonitor\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class RecordDiskMetricsCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('anotherDisk');
    }

    /** @test */
    public function it_will_record_0_files_for_empty_disks()
    {
        $this->artisan(LaravelDiskMonitorCommand::class)
            ->assertExitCode(0);

        $this->assertCount(1, DiskMonitorEntry::get());

        $entry = DiskMonitorEntry::last();
        $this->assertEquals(0, $entry->file_count);
    }

    /** @test */
    public function it_will_record_the_file_count_for_a_single_disk()
    {
        $this->artisan(LaravelDiskMonitorCommand::class)
            ->assertExitCode(0);

        $entry = DiskMonitorEntry::last();
        $this->assertEquals(0, $entry->file_count);

        Storage::disk('local')->put('test.txt', 'text');
        $this->artisan(LaravelDiskMonitorCommand::class)
            ->assertExitCode(0);
        $entry = DiskMonitorEntry::last();
        $this->assertEquals(1, $entry->file_count);
    }

    /** @test */
    public function it_will_record_the_file_count_for_multiple_disks()
    {
        config()->set('laravel-disk-monitor.disk_names', ['local', 'anotherDisk']);
        Storage::disk('anotherDisk')->put('test.txt', 'text');

        $this->artisan(LaravelDiskMonitorCommand::class)
            ->assertExitCode(0);

        $entries = DiskMonitorEntry::get();
        $this->assertCount(2, $entries);

        $this->assertEquals('local', $entries[0]->disk_name);
        $this->assertEquals(0, $entries[0]->file_count);

        $this->assertEquals('anotherDisk', $entries[1]->disk_name);
        $this->assertEquals(1, $entries[1]->file_count);
    }
}
