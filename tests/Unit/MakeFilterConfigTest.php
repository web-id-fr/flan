<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use WebId\Flan\Tests\TestCase;

class MakeFilterConfigTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        config()->set('flan.filter_config_directory',  __DIR__ . '/../../config/FilterConfigs');
        config()->set('filesystems.disks', [
            'filter_config' => [
                'driver' => 'local',
                'root' => __DIR__ . '/../../config/FilterConfigs',
            ],
        ]);
    }

    /** @test */
//    public function it_can_create_filter_class()
//    {
//        Storage::fake('filter_config');
//
//        $this->artisan('make:filter:config Ingredient')
//            ->assertExitCode(0);
//
//        dd(Storage::disk('filter_config')->allFiles());
//    }
}
