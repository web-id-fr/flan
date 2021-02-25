<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class MutationFilterTest extends TestCase
{
    /** @test */
    public function it_can_filter_with_concat_mutation()
    {
        $results = $this->getPizzaFilter(['ingredients']);

        $this->assertEquals('Tomato sauce, Mozzarella, Basil', $results[0]->ingredients);
    }

    /** @test */
    public function it_can_filter_with_array_mutation()
    {
        $results = $this->getPizzaFilter(['active'], ['active' => [
            'strategy' => 'contains',
            'term' => 0
        ]]);

        $this->assertCount(1, $results);
    }

    /** @test */
    public function it_can_filter_with_sub_select()
    {
        $results = $this->getPizzaFilter(['count_ingredients']);

        $this->assertEquals(3, $results[0]->count_ingredients);
        $this->assertEquals(6, $results[1]->count_ingredients);
        $this->assertEquals(6, $results[2]->count_ingredients);
        $this->assertEquals(5, $results[3]->count_ingredients);
    }
}
