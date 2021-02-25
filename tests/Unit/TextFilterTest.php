<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class TextFilterTest extends TestCase
{
    /** @test */
    public function it_can_sort_by_ascending(): void
    {
        $results = $this->getPizzaFilter(['name'], [], ['sortBy' => 'name', 'descending' => false]);

        $this->assertEquals('Capricciosa', $results[0]->name);
        $this->assertEquals('Diavola', $results[1]->name);
        $this->assertEquals('Margherita', $results[2]->name);
        $this->assertEquals('Napoletana', $results[3]->name);
    }

    /** @test */
    public function it_can_sort_by_descending(): void
    {
        $results = $this->getPizzaFilter(['name'], [], ['sortBy' => 'name', 'descending' => true]);

        $this->assertEquals('Napoletana', $results[0]->name);
        $this->assertEquals('Margherita', $results[1]->name);
        $this->assertEquals('Diavola', $results[2]->name);
        $this->assertEquals('Capricciosa', $results[3]->name);
    }

    /** @test */
    public function it_can_filter_with_contains_strategy(): void
    {
        $results = $this->getPizzaFilter(['name'], ['name' => ['strategy' => 'contains', 'term' => 'gheri']]);

        $this->assertCount(1, $results);
        $this->assertEquals('Margherita', $results[0]->name);
    }

    /** @test */
    public function it_can_filter_with_ignore_strategy(): void
    {
        $results = $this->getPizzaFilter(['name'], ['name' => ['strategy' => 'ignore', 'term' => 'gheri']]);

        $this->assertCount(3, $results);
        $this->assertEquals('Capricciosa', $results[0]->name);
        $this->assertEquals('Diavola', $results[1]->name);
        $this->assertEquals('Napoletana', $results[2]->name);
    }
}
