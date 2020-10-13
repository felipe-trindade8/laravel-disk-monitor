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
    public function it_will_record_the_file_count_for_a_disk()
    {
        //Storage::disk('local')->put('test.txt', 'test');
        $this->artisan(LaravelDiskMonitorCommand::class)
            ->assertExitCode(0);

        $entry = DiskMonitorEntry::last();
        $this->assertEquals(0, $entry->file_count);

        Storage::put('test.txt', 'text');
        $this->artisan(LaravelDiskMonitorCommand::class)
            ->assertExitCode(0);
        $entry = DiskMonitorEntry::last();
        $this->assertEquals(1, $entry->file_count);
    }
}
