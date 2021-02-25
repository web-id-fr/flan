<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class NumberFilterTest extends TestCase
{
    /** @test */
    public function it_can_sort_by_ascending(): void
    {
        $results = $this->getPizzaFilter(['price'], [], ['sortBy' => 'price', 'descending' => false]);

        $this->assertEquals(null, $results[0]->price);
        $this->assertEquals(10.20, $results[1]->price);
        $this->assertEquals(11.50, $results[2]->price);
        $this->assertEquals(12.45, $results[3]->price);
    }

    /** @test */
    public function it_can_sort_by_descending(): void
    {
        $results = $this->getPizzaFilter(['price'], [], ['sortBy' => 'price', 'descending' => true]);

        $this->assertEquals(12.45, $results[0]->price);
        $this->assertEquals(11.50, $results[1]->price);
        $this->assertEquals(10.20, $results[2]->price);
        $this->assertEquals(null, $results[3]->price);
    }

    /** @test */
    public function it_can_filter_with_equals_strategy(): void
    {
        $results = $this->getPizzaFilter(['price'], ['price' => ['strategy' => 'equals', 'term' => 10.20]]);

        $this->assertCount(1, $results);
        $this->assertEquals(10.20, $results[0]->price);
    }

    /** @test */
    public function it_can_filter_with_between_strategy(): void
    {
        $results = $this->getPizzaFilter(['price'], [
            'price' => [
                'strategy' => 'between',
                'term' => 10.30,
                'second_term' => 12.30
            ]
        ]);

        $this->assertCount(1, $results);
        $this->assertEquals(11.50, $results[0]->price);
    }

    /** @test */
    public function it_can_filter_with_bigger_strategy(): void
    {
        $results = $this->getPizzaFilter(['price'], ['price' => ['strategy' => 'bigger', 'term' => 10.30]]);

        $this->assertCount(2, $results);
        $this->assertEquals(12.45, $results[0]->price);
        $this->assertEquals(11.50, $results[1]->price);
    }

    /** @test */
    public function it_can_filter_with_lower_strategy(): void
    {
        $results = $this->getPizzaFilter(['price'], ['price' => ['strategy' => 'lower', 'term' => 10.30]]);

        $this->assertCount(1, $results);
        $this->assertEquals(10.20, $results[0]->price);
    }

    /** @test */
    public function it_can_filter_with_not_in_strategy(): void
    {
        $results = $this->getPizzaFilter(['price'], ['price' => ['strategy' => 'not_in', 'term' => [10.20, 11.50]]]);

        $this->assertCount(1, $results);
        $this->assertEquals(12.45, $results[0]->price);
    }

    /** @test */
    public function it_can_filter_with_is_null_strategy(): void
    {
        $results = $this->getPizzaFilter(['price'], ['price' => ['strategy' => 'is_null']]);

        $this->assertCount(1, $results);
        $this->assertEquals(null, $results[0]->price);
    }
}
