<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class SelectFilterTest extends TestCase
{
    /** @test */
    public function it_can_sort_by_ascending(): void
    {
        $results = $this->getPizzaFilter(['active'], [], ['sortBy' => 'active', 'descending' => false]);

        $this->assertEquals(0, $results[0]->active);
        $this->assertEquals(1, $results[1]->active);
        $this->assertEquals(1, $results[2]->active);
        $this->assertEquals(1, $results[3]->active);
    }


    /** @test */
    public function it_can_sort_by_descending(): void
    {
        $results = $this->getPizzaFilter(['active'], [], ['sortBy' => 'active', 'descending' => true]);

        $this->assertEquals(1, $results[0]->active);
        $this->assertEquals(1, $results[1]->active);
        $this->assertEquals(1, $results[2]->active);
        $this->assertEquals(0, $results[3]->active);
    }

    /** @test */
    public function it_can_filter_with_select_filter(): void
    {
        $results = $this->getPizzaFilter(['active'], ['active' => ['term' => 0]]);

        $this->assertCount(1, $results);
        $this->assertEquals(0, $results[0]->active);
    }
}
