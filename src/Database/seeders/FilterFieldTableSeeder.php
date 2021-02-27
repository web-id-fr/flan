<?php

namespace WebId\Flan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilterFieldTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('filter_fields')->insert([
            [
                'filter_id' => 1,
                'name' => 'id',
                'content' => '[]'
            ],
            [
                'filter_id' => 1,
                'name' => 'name',
                'content' => '[]'
            ],
            [
                'filter_id' => 1,
                'name' => 'price',
                'content' => '[]'
            ],
            [
                'filter_id' => 1,
                'name' => 'ingredients',
                'content' => '[]'
            ],
            [
                'filter_id' => 1,
                'name' => 'active',
                'content' => '[]'
            ],
            [
                'filter_id' => 1,
                'name' => 'created_at',
                'content' => '[]'
            ],
            [
                'filter_id' => 1,
                'name' => 'created_at_with_time',
                'content' => '[]'
            ],
            [
                'filter_id' => 1,
                'name' => 'count_ingredients',
                'content' => '{"strategy":"bigger","term":"4"}'
            ],
            [
                'filter_id' => 2,
                'name' => 'id',
                'content' => '[]'
            ],
            [
                'filter_id' => 2,
                'name' => 'name',
                'content' => '[]'
            ],
            [
                'filter_id' => 2,
                'name' => 'price',
                'content' => '[]'
            ],
            [
                'filter_id' => 2,
                'name' => 'ingredients',
                'content' => '{"strategy":"ignore","term":"Mushroom"}'
            ],
            [
                'filter_id' => 2,
                'name' => 'active',
                'content' => '[]'
            ],
            [
                'filter_id' => 2,
                'name' => 'created_at',
                'content' => '[]'
            ],
            [
                'filter_id' => 2,
                'name' => 'created_at_with_time',
                'content' => '[]'
            ],
            [
                'filter_id' => 2,
                'name' => 'count_ingredients',
                'content' => '[]'
            ],
        ]);
    }
}
