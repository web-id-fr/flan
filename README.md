# FLAN - Filter like a ninja 🥷

## Installation

Require this package with composer.

```shell
composer require web-id/flan
```
FLAN uses Laravel Package Auto-Discovery, and doesn't require you to manually add the ServiceProvider.

Copy the package config to your local config with the publish command:
```shell
php artisan vendor:publish --provider="WebId\Flan\FlanServiceProvider"
```

Finally, run the filter tables migration
```shell
php artisan migrate
```

## Usage

You can create a filter with :
```shell
php artisan filter:create User
```

or eventually just the Filter class :
```shell
php artisan make:filter:class User
```

or just the Filter config :
```shell
php artisan make:filter:config User
```

## Front usage

In your controller, you can send to the view `filterConfig` of products for example like this :
```php
return view('products.index', [
    'filterConfigs' => FilterFactory::create('products')->getConfiguration()
]);
```

And the request need to be :

```json
{
  "filter_name": "users", //Required
  "fields": [ //Required
    "id",
    "name"
  ],
  "name": {
    "strategy": "contains",
    "term": "doe"
  },
  "page": 1, //Required
  "descending": 1, //Boolean
  "rowsPerPage": 10
}
```

## Fields
### Text

Search for text containing 'John'
```json
{
  strategy: "contains",
  term: "John"
}
```
Search for text not containing 'John'
```json
{
  strategy: "ignore",
  term: "John"
}
```

### Number

Search for number = 10
```json
{
  strategy: "equals",
  term: 10
}
```

Search for 10 < number < 13
```json
{
  strategy: "between",
  term: 10,
  second_term: 13
}
```

Search for number > 10
```json
{
  strategy: "bigger",
  term: 10
}
```

Search for number < 10
```json
{
  strategy: "lower",
  term: 10
}
```

Search for number not equals than values
```json
{
  strategy: "not_in",
  term: [10, 12, 20]
}
```

Search for number nullable
```json
{
  strategy: "is_null"
}
```

### Date

Search for date equals 2021-12-25
```json
{
  strategy: "equals",
  date: "2021-12-25"
}
```

Search for date between 2021-12-25 and 2021-12-31
```json
{
  strategy: "between",
  date: "2021-12-25",
  second_date: "2021-12-31"
}
```

Search for date matching this week
```json
{
  strategy: "current_week"
}
```

Search for date matching the previous week
```json
{
  strategy: "past_week"
}
```

Search for date matching this month
```json
{
  strategy: "current_month"
}
```

Search for date matching the previous month
```json
{
  strategy: "past_month"
}
```

### Select

Search for row = 'active'
```json
{
  term: "active"
}
```

### Checkbox

Search for row like one of values
```json
[
  'red', 'square', 'light'
]
```