<?php

namespace WebId\Flan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedModeTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feed_modes')->insert([
            [
                'id' => 1,
                'name' => 'Omnivore',
            ], [
                'id' => 2,
                'name' => 'Vegetarian',
            ], [
                'id' => 3,
                'name' => 'Vegan',
            ],
        ]);
    }
}
