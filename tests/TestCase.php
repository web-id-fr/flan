<?php

namespace WebId\Flan\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use WebId\Flan\Database\Seeders\FeedModeTableSeeder;
use WebId\Flan\Database\Seeders\FilterFieldTableSeeder;
use WebId\Flan\Database\Seeders\FilterTableSeeder;
use WebId\Flan\Database\Seeders\IngredientPizzaTableSeeder;
use WebId\Flan\Database\Seeders\IngredientTableSeeder;
use WebId\Flan\Database\Seeders\PizzaTableSeeder;
use WebId\Flan\Filters\PizzaFilter;
use WebId\Flan\FlanServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'WebId\\Flan\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FlanServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        config()->set('database.default', 'mysql');
        config()->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => [],
        ]);

        $this->dropTables();

        include_once __DIR__ . '/../src/Database/migrations/testing/create_ingredients_table.php';
        include_once __DIR__ . '/../src/Database/migrations/testing/create_feed_modes_table.php';
        include_once __DIR__ . '/../src/Database/migrations/testing/create_pizzas_table.php';
        include_once __DIR__ . '/../src/Database/migrations/testing/create_ingredient_pizza_table.php';
        include_once __DIR__ . '/../src/Database/migrations/create_filters_tables.php';

        (new \CreateIngredientsTable())->up();
        (new \CreateFeedModesTable())->up();
        (new \CreatePizzasTable())->up();
        (new \CreateIngredientPizzaTable())->up();
        (new \CreateFiltersTables())->up();

        (new IngredientTableSeeder())->run();
        (new FeedModeTableSeeder())->run();
        (new PizzaTableSeeder())->run();
        (new IngredientPizzaTableSeeder())->run();
        (new FilterTableSeeder())->run();
        (new FilterFieldTableSeeder())->run();
    }

    /**
     * @param string $filterClass
     * @param array $fields
     * @param array $filters
     * @param array $orderBy
     * @param int $paginate
     * @return mixed
     */
    protected function getFilter(string $filterClass, array $fields, array $filters = [], array $orderBy = [], int $paginate = 5)
    {
        return app($filterClass)
            ->select($fields)
            ->filter($filters)
            ->orderBy($orderBy['sortBy'] ?? '')
            ->sort($orderBy['descending'] ?? false)
            ->paginate($paginate)
            ->toArray()['data'];
    }

    /**
     * @param array $fields
     * @param array $filters
     * @param array $orderBy
     * @param int $paginate
     * @return mixed
     */
    protected function getPizzaFilter(array $fields, array $filters = [], array $orderBy = [], int $paginate = 5)
    {
        return $this->getFilter(PizzaFilter::class, $fields, $filters, $orderBy, $paginate);
    }

    private function dropTables(): void
    {
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            Schema::drop($table->Tables_in_flan);
        }
        DB::statement("SET FOREIGN_KEY_CHECKS = 1");
    }
}
