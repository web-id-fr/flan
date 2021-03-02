<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use WebId\Flan\Tests\TestCase;

class MakeFilterConfigTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_create_filter_class()
    {
        File::deleteDirectory(config('flan.filter_config_directory'));

        $this->artisan('make:filter:config Ingredient')
            ->assertExitCode(0);

        $this->assertFileExists(config('flan.filter_config_directory') . '/ingredients.php');
    }
}
