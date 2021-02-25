<?php

namespace WebId\Flan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientPizzaTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ingredient_pizza')->insert([
            //Margherita
            [
                'pizza_id' => 1,
                'ingredient_id' => 1,
            ],
            [
                'pizza_id' => 1,
                'ingredient_id' => 5,
            ],
            [
                'pizza_id' => 1,
                'ingredient_id' => 6,
            ],
            //Capricciosa
            [
                'pizza_id' => 2,
                'ingredient_id' => 1,
            ],
            [
                'pizza_id' => 2,
                'ingredient_id' => 5,
            ],
            [
                'pizza_id' => 2,
                'ingredient_id' => 7,
            ],
            [
                'pizza_id' => 2,
                'ingredient_id' => 4,
            ],
            [
                'pizza_id' => 2,
                'ingredient_id' => 8,
            ],
            [
                'pizza_id' => 2,
                'ingredient_id' => 9,
            ],
            //Diavola
            [
                'pizza_id' => 3,
                'ingredient_id' => 1,
            ],
            [
                'pizza_id' => 3,
                'ingredient_id' => 12,
            ],
            [
                'pizza_id' => 3,
                'ingredient_id' => 5,
            ],
            [
                'pizza_id' => 3,
                'ingredient_id' => 10,
            ],
            [
                'pizza_id' => 3,
                'ingredient_id' => 6,
            ],
            [
                'pizza_id' => 3,
                'ingredient_id' => 11,
            ],
            //Napoletana
            [
                'pizza_id' => 4,
                'ingredient_id' => 1,
            ],
            [
                'pizza_id' => 4,
                'ingredient_id' => 5,
            ],
            [
                'pizza_id' => 4,
                'ingredient_id' => 11,
            ],
            [
                'pizza_id' => 4,
                'ingredient_id' => 9,
            ],
            [
                'pizza_id' => 4,
                'ingredient_id' => 6,
            ],
        ]);
    }
}
