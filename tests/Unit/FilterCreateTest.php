<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use WebId\Flan\Tests\TestCase;

class FilterCreateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        config()->set('flan.filter_config_directory',  __DIR__ . '/../../config/FilterConfigs');
        config()->set('flan.filter_class_directory', __DIR__ . '/../../src/Filters/FilterClasses');
        config()->set('flan.default_model_namespace', 'WebId\\Flan\\Models');
        config()->set('flan.default_filter_class_namespace', 'WebId\\Flan\\Filters\\FilterClasses');
    }

    /** @test */
    public function it_can_create_filter_class_and_filter_config()
    {
        File::deleteDirectory(config('flan.filter_config_directory'));
        File::deleteDirectory(config('flan.filter_class_directory'));

        $this->artisan('filter:create Ingredient')
            ->assertExitCode(0);

        $this->assertFileExists(config('flan.filter_config_directory') . '/ingredients.php');
        $this->assertFileExists(config('flan.filter_class_directory') . '/IngredientFilter.php');
    }
}
