<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class CheckboxFilterTest extends TestCase
{
    /** @test */
    public function it_can_sort_by_ascending(): void
    {
        $results = $this->getPizzaFilter(['gss'], [], ['sortBy' => 'gss', 'descending' => false]);

        $this->assertEquals(1, $results[0]->gss);
        $this->assertEquals(2, $results[1]->gss);
        $this->assertEquals(2, $results[2]->gss);
        $this->assertEquals(3, $results[3]->gss);
    }


    /** @test */
    public function it_can_sort_by_descending(): void
    {
        $results = $this->getPizzaFilter(['gss'], [], ['sortBy' => 'gss', 'descending' => true]);

        $this->assertEquals(3, $results[0]->gss);
        $this->assertEquals(2, $results[1]->gss);
        $this->assertEquals(2, $results[2]->gss);
        $this->assertEquals(1, $results[3]->gss);
    }

    /** @test */
    public function it_can_filter_with_checkbox_filter_and_one_selection(): void
    {
        $results = $this->getPizzaFilter(['gss'], ['gss' => [2]]);

        $this->assertCount(2, $results);
        $this->assertEquals(2, $results[0]->gss);
        $this->assertEquals(2, $results[1]->gss);
    }

    /** @test */
    public function it_can_filter_with_checkbox_filter_and_multiple_selections(): void
    {
        $results = $this->getPizzaFilter(['gss'], ['gss' => [2, 3]]);

        $this->assertCount(3, $results);
        $this->assertEquals(2, $results[0]->gss);
        $this->assertEquals(2, $results[1]->gss);
        $this->assertEquals(3, $results[2]->gss);
    }
}
