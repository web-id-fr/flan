<?php

namespace Tests\Unit;

use WebId\Flan\Tests\TestCase;

class DateFilterTest extends TestCase
{
    /** @test */
    public function it_can_sort_by_ascending(): void
    {
        $results = $this->getPizzaFilter(['created_at'], [], ['sortBy' => 'created_at', 'descending' => false]);

        $this->assertEquals('2020-01-01', $results[0]->created_at);
        $this->assertEquals('2020-01-02', $results[1]->created_at);
        $this->assertEquals('2020-01-03', $results[2]->created_at);
        $this->assertEquals('2020-01-04', $results[3]->created_at);
    }

    /** @test */
    public function it_can_sort_by_descending(): void
    {
        $results = $this->getPizzaFilter(['created_at'], [], ['sortBy' => 'created_at', 'descending' => true]);

        $this->assertEquals('2020-01-04', $results[0]->created_at);
        $this->assertEquals('2020-01-03', $results[1]->created_at);
        $this->assertEquals('2020-01-02', $results[2]->created_at);
        $this->assertEquals('2020-01-01', $results[3]->created_at);
    }

    /** @test */
    public function it_can_filter_with_equals_strategy(): void
    {
        $results = $this->getPizzaFilter(['created_at'], [
            'created_at' => [
                'strategy' => 'equals',
                'date' => '2020-01-01',
            ],
        ]);

        $this->assertCount(1, $results);
        $this->assertEquals('2020-01-01', $results[0]->created_at);
    }

    /** @test */
    public function it_can_filter_with_between_strategy(): void
    {
        $results = $this->getPizzaFilter(['created_at'], [
            'created_at' => [
                'strategy' => 'between',
                'date' => '2020-01-03',
                'second_date' => '2020-01-04',
            ],
        ]);

        $this->assertCount(2, $results);
        $this->assertEquals('2020-01-03', $results[0]->created_at);
        $this->assertEquals('2020-01-04', $results[1]->created_at);
    }

    /** @test */
    public function it_use_config_sql_date_format_output(): void
    {
        config()->set('flan.default_sql_date_format_output', '%d/%m/%Y');
        $results = $this->getPizzaFilter(['created_at'], [
            'created_at' => [
                'strategy' => 'equals',
                'date' => '2020-01-03',
            ],
        ]);

        $this->assertCount(1, $results);
        $this->assertEquals('03/01/2020', $results[0]->created_at);
    }

    /** @test */
//    public function it_use_config_sql_datetime_format_output(): void
//    {
//        config()->set('flan.default_sql_datetime_format_output', '%d/%m/%Y à %Hh%i');
//        $results = $this->getPizzaFilter(['created_at_with_time'], [
//            'created_at' => [
//                'strategy' => 'equals',
//                'date' => '2020-01-03',
//            ]
//        ]);
//
//        $this->assertCount(1, $results);
//        $this->assertEquals('03/01/2020 à 00h00', $results[0]->created_at);
//    }
}
