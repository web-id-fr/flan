<?php

namespace Tests\Feature;

use WebId\Flan\Tests\TestCase;

class FilterControllerTest extends TestCase
{
    const _ROUTE_INDEX = 'filters.index';
    const _ROUTE_FILTER = 'filters.filter';
    const _ROUTE_STORE = 'filters.store';
    const _ROUTE_DESTROY = 'filters.destroy';

    /** @test */
    public function it_can_get_filter(): void
    {
        $response = $this->get(route(self::_ROUTE_INDEX, ['filter_name' => 'pizzas']))
            ->assertSuccessful();

        $filters = $response->json('data');
        $this->assertCount(2, $filters);
        $this->assertEquals('With min 4 ingredients', $filters[0]['label']);
        $this->assertEquals('Without Mushroom', $filters[1]['label']);
    }

    /** @test */
    public function it_can_get_empty_filter(): void
    {
        $response = $this->get(route(self::_ROUTE_INDEX, ['filter_name' => 'nothing']))
            ->assertSuccessful();

        $filters = $response->json('data');
        $this->assertEmpty($filters);
    }

    /** @test */
    public function it_can_save_new_filter(): void
    {
        $response = $this->post(route(self::_ROUTE_STORE), [
            'filter_name' => 'pizzas',
            'label' => 'With Mozzarella',
            'fields' => [
                'ingredients' => ['strategy' => 'contains', 'term' => 'Mozzarella'],
            ],
        ])
            ->assertSuccessful();

        $filter = $response->json('data');
        $this->assertEquals('pizzas', $filter['filter_name']);
        $this->assertEquals('With Mozzarella', $filter['label']);
        $this->assertEquals([
            "name" => "ingredients",
            "content" => [
                "strategy" => "contains",
                "term" => "Mozzarella",
            ],
        ], $filter['fields'][0]);
    }

    /** @test */
    public function it_can_delete_filter(): void
    {
        $this->delete(route(self::_ROUTE_DESTROY, ['filter' => 1]))
            ->assertStatus(204);

        $this->assertDatabaseMissing('filters', [
            'id' => 1,
        ]);

        $this->assertDatabaseMissing('filter_fields', [
            'filter_id' => 1,
        ]);
    }

    /** @test */
    public function it_can_post_basic_filter(): void
    {
        $response = $this->post(route(self::_ROUTE_FILTER), [
            'page' => 1,
            'rowsPerPage' => 10,
            'filter_name' => 'pizzas',
            'fields' => [
                "id", "name", "price", "ingredients", "active", "created_at", "created_at_with_time", "count_ingredients",
            ],
        ])
            ->assertSuccessful();

        $data = $response->json('data');
        $this->assertCount(4, $data);
    }

    /** @test */
    public function it_can_post_filter_for_next_page_with_max_2_rows(): void
    {
        $response = $this->post(route(self::_ROUTE_FILTER), [
            'page' => 2,
            'rowsPerPage' => 2,
            'filter_name' => 'pizzas',
            'fields' => [
                "id", "name", "price", "ingredients", "active", "created_at", "created_at_with_time", "count_ingredients",
            ],
        ])
            ->assertSuccessful();

        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    /** @test */
    public function it_can_post_filter_for_one_field_and_descending(): void
    {
        $response = $this->post(route(self::_ROUTE_FILTER), [
            'page' => 1,
            'rowsPerPage' => 10,
            'filter_name' => 'pizzas',
            'fields' => [
                "name",
            ],
            'sortBy' => 'name',
            'descending' => 1,
        ])
            ->assertSuccessful();

        $data = $response->json('data');

        $this->assertEquals('Napoletana', $data[0]['name']);
        $this->assertEquals('Margherita', $data[1]['name']);
        $this->assertEquals('Diavola', $data[2]['name']);
        $this->assertEquals('Capricciosa', $data[3]['name']);
    }

    /** @test */
    public function it_can_un_active_routes(): void
    {
        config()->set('flan.routing.filters.active', false);
        $this->get(route(self::_ROUTE_INDEX, ['filter_name' => 'pizzas']))
            ->assertNotFound();

        $this->post(route(self::_ROUTE_STORE), [
            'filter_name' => 'pizzas',
            'label' => 'With Mozzarella',
            'fields' => [
                'ingredients' => ['strategy' => 'contains', 'term' => 'Mozzarella'],
            ],
        ])
            ->assertNotFound();

        $this->delete(route(self::_ROUTE_DESTROY, ['filter' => 1]))
            ->assertNotFound();

        $this->post(route(self::_ROUTE_FILTER), [
            'page' => 1,
            'rowsPerPage' => 10,
            'filter_name' => 'pizzas',
            'fields' => [
                "id", "name", "price", "ingredients", "active", "created_at", "created_at_with_time", "count_ingredients",
            ],
        ])
            ->assertNotFound();
    }
}
