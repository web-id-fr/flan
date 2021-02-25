<?php

namespace WebId\Flan\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use WebId\Flan\Database\Seeders\FeedModeTableSeeder;
use WebId\Flan\Database\Seeders\IngredientPizzaTableSeeder;
use WebId\Flan\Database\Seeders\IngredientTableSeeder;
use WebId\Flan\Database\Seeders\PizzaTableSeeder;
use WebId\Flan\Filters\PizzaFilter;
use WebId\Flan\FlanServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        config()->set('database.default', 'mysql');
        config()->set('database.connections.mysql', [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE_FILTER_SYSTEM', 'filterPackage'),
            'username' => env('DB_USERNAME_FILTER_SYSTEM', 'root'),
            'password' => env('DB_PASSWORD_FILTER_SYSTEM', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]);

        $this->dropTables();

        include_once __DIR__.'/../src/database/migrations/create_ingredients_table.php';
        include_once __DIR__.'/../src/database/migrations/create_feed_modes_table.php';
        include_once __DIR__.'/../src/database/migrations/create_pizzas_table.php';
        include_once __DIR__.'/../src/database/migrations/create_ingredient_pizza_table.php';

        (new \CreateIngredientsTable())->up();
        (new \CreateFeedModesTable())->up();
        (new \CreatePizzasTable())->up();
        (new \CreateIngredientPizzaTable())->up();

        (new IngredientTableSeeder())->run();
        (new FeedModeTableSeeder())->run();
        (new PizzaTableSeeder())->run();
        (new IngredientPizzaTableSeeder())->run();
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
        foreach($tables as $table){
            Schema::drop($table->Tables_in_filterpackage);
        }
        DB::statement("SET FOREIGN_KEY_CHECKS = 1");
    }
}
