<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class PaginateFilterTest extends TestCase
{
    /** @test */
    public function it_can_paginate_by_3(): void
    {
        $results = $this->getPizzaFilter(['id', 'name'], [], [], 2);

        $this->assertCount(2, $results);
    }

    /** @test */
    public function it_can_not_paginate(): void
    {
        $results = $this->getPizzaFilter(['id', 'name'], [], [], -1);

        $this->assertCount(4, $results);
    }
}
