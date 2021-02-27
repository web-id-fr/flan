<?php

namespace WebId\Flan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilterTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('filters')->insert([
            [
                'id' => 1,
                'filter_name' => 'pizzas',
                'label' => 'With min 4 ingredients',
            ],
            [
                'id' => 2,
                'filter_name' => 'pizzas',
                'label' => 'Without Mushroom',
            ],
        ]);
    }
}
