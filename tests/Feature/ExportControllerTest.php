<?php

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use WebId\Flan\Filters\Base\FilterExport;
use WebId\Flan\Tests\TestCase;

class ExportControllerTest extends TestCase
{
    const _ROUTE_EXPORT = 'filters.export';

    /** @test */
    public function it_can_export_pizzas()
    {
        config()->set('flan.default_filter_class_namespace', '\WebId\Flan\Filters');
        Excel::fake();

        $this->post(route(self::_ROUTE_EXPORT), [
            'page' => 1,
            'rowsPerPage' => 10,
            'filter_name' => 'pizzas',
            'fields' => [
                "name",
            ],
        ])
            ->assertSuccessful();

        Excel::assertDownloaded(
            'Pizzas_' . Carbon::now()->format('Y-m-d') . '.xlsx',
            function (FilterExport $export) {
                return $export->collection()->contains('name', 'Margherita')
                    && $export->collection()->contains('name', 'Capricciosa')
                    && $export->collection()->contains('name', 'Diavola')
                    && $export->collection()->contains('name', 'Napoletana');
            }
        );
    }

    /** @test */
    public function it_can_un_active_route()
    {
        config()->set('flan.routing.export.active', false);

        $this->post(route(self::_ROUTE_EXPORT), [
            'page' => 1,
            'rowsPerPage' => 10,
            'filter_name' => 'pizzas',
            'fields' => [
                "name",
            ],
        ])
            ->assertNotFound();
    }
}
