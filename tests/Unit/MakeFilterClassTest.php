<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use WebId\Flan\Tests\TestCase;

class MakeFilterClassTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        config()->set('flan.filter_class_directory', __DIR__ . '/../../src/Filters/FilterClasses');
        config()->set('flan.default_model_namespace', 'WebId\\Flan\\Models');
        config()->set('flan.default_filter_class_namespace', 'WebId\\Flan\\Filters\\FilterClasses');
    }

    /** @test */
    public function it_can_create_filter_class(): void
    {
        File::deleteDirectory(config('flan.filter_class_directory'));

        $this->artisan('make:filter:class Ingredient')
            ->assertExitCode(0);

        $this->assertFileExists(config('flan.filter_class_directory') . '/IngredientFilter.php');
    }
}
