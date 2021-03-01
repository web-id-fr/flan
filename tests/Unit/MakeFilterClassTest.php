<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use WebId\Flan\Tests\TestCase;

class MakeFilterClassTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        config()->set('flan.filter_class_directory', __DIR__ . '/../../src/Filters');
        config()->set('flan.default_model_namespace', 'WebId\\Flan\\Models');
        config()->set('flan.default_filter_class_namespace', 'WebId\\Flan\\Filters');
        config()->set('filesystems.disks', [
            'filter_class' =>  [
                'driver' => 'local',
                'root' => __DIR__ . '/../../src/Filters',
            ],
        ]);
    }

    /** @test */
    public function it_can_create_filter_class()
    {
        Storage::fake('filter_class');

        $this->artisan('make:filter:class Ingredient')
            ->assertExitCode(0);

        dd(Storage::disk('filter_class')->allFiles());
    }
}
