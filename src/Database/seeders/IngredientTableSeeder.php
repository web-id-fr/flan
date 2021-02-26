<?php

namespace WebId\Flan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ingredients')->insert([
            [
                'id' => 1,
                'name' => 'Tomato sauce',
            ],
            [
                'id' => 2,
                'name' => 'Goat forming',
            ],
            [
                'id' => 3,
                'name' => 'Cheese',
            ],
            [
                'id' => 4,
                'name' => 'Olive',
            ],
            [
                'id' => 5,
                'name' => 'Mozzarella',
            ],
            [
                'id' => 6,
                'name' => 'Basil',
            ],
            [
                'id' => 7,
                'name' => 'Mushroom',
            ],
            [
                'id' => 8,
                'name' => 'Ham',
            ],
            [
                'id' => 9,
                'name' => 'Anchovy',
            ],
            [
                'id' => 10,
                'name' => 'Piment',
            ],
            [
                'id' => 11,
                'name' => 'Parmesan',
            ],
            [
                'id' => 12,
                'name' => 'Neapolitan sausage',
            ],
        ]);
    }
}
