<?php

namespace WebId\Flan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PizzaTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pizzas')->insert([
            [
                'id' => 1,
                'name' => 'Margherita',
                'price' => 12.45,
                'feed_mode_id' => 2,
                'active' => 1,
                'created_at' => '2020-01-02 00:00:00',
            ], [
                'id' => 2,
                'name' => 'Capricciosa',
                'price' => 10.20,
                'feed_mode_id' => 1,
                'active' => 1,
                'created_at' => '2020-01-01 00:00:00',
            ], [
                'id' => 3,
                'name' => 'Diavola',
                'price' => 11.50,
                'feed_mode_id' => 1,
                'active' => 1,
                'created_at' => '2020-01-03 00:00:00',
            ], [
                'id' => 4,
                'name' => 'Napoletana',
                'price' => null,
                'feed_mode_id' => 1,
                'active' => 0,
                'created_at' => '2020-02-01 00:00:00',
            ],
        ]);
    }
}
