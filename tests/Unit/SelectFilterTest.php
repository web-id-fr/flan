<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class SelectFilterTest extends TestCase
{
    /** @test */
    public function it_can_sort_by_ascending(): void
    {
        $results = $this->getPizzaFilter(['feed_mode_id'], [], ['sortBy' => 'feed_mode_id', 'descending' => false]);

        $this->assertEquals(1, $results[0]->feed_mode_id);
        $this->assertEquals(1, $results[1]->feed_mode_id);
        $this->assertEquals(1, $results[2]->feed_mode_id);
        $this->assertEquals(2, $results[3]->feed_mode_id);
    }

    /** @test */
    public function it_can_sort_by_descending(): void
    {
        $results = $this->getPizzaFilter(['feed_mode_id'], [], ['sortBy' => 'feed_mode_id', 'descending' => true]);

        $this->assertEquals(2, $results[0]->feed_mode_id);
        $this->assertEquals(1, $results[1]->feed_mode_id);
        $this->assertEquals(1, $results[2]->feed_mode_id);
        $this->assertEquals(1, $results[3]->feed_mode_id);
    }

    /** @test */
    public function it_can_filter(): void
    {
        $results = $this->getPizzaFilter(['feed_mode_id'], ['feed_mode_id' => ['term' => 1]]);

        $this->assertCount(3, $results);
    }

    /** @test */
    public function it_can_sort_by_ascending_with_relation(): void
    {
        $results = $this->getPizzaFilter(['feed_mode'], [], ['sortBy' => 'feed_mode', 'descending' => false]);

        $this->assertEquals('Omnivore', $results[0]->feed_mode);
        $this->assertEquals('Omnivore', $results[1]->feed_mode);
        $this->assertEquals('Omnivore', $results[2]->feed_mode);
        $this->assertEquals('Vegetarian', $results[3]->feed_mode);
    }

    /** @test */
    public function it_can_sort_by_descending_with_relation(): void
    {
        $results = $this->getPizzaFilter(['feed_mode'], [], ['sortBy' => 'feed_mode', 'descending' => true]);

        $this->assertEquals('Vegetarian', $results[0]->feed_mode);
        $this->assertEquals('Omnivore', $results[1]->feed_mode);
        $this->assertEquals('Omnivore', $results[2]->feed_mode);
        $this->assertEquals('Omnivore', $results[3]->feed_mode);
    }

    /** @test */
    public function it_can_filter_with_relation(): void
    {
        $results = $this->getPizzaFilter(['feed_mode'], ['feed_mode' => ['term' => 'Omnivore']]);

        $this->assertCount(3, $results);
    }
}
