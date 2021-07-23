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

You can create a filter with:

```shell
php artisan filter:create User
```

or eventually just the Filter class:

```shell
php artisan make:filter:class User
```

or just the Filter config:

```shell
php artisan make:filter:config User
```

## Filter configuration

You can find the configuration files for your Filters in the folder `config/FilterConfigs`

A configuration file is made of two entries `name` and `filters`:

```php
return [
    'name' => 'myfilter',
    'filters' => [
        [
            'text' => 'Model ID',
            'name' => 'id',
            'active' => true,
            'field' => [
                'type' => 'number',
            ],
        ],
        // [ ... ]
    ],
];
```

### Configuration for any field type:

* `filters.*.text` is the HTML input label
* `filters.*.name` is the HTML input name attribute
* `filters.*.active` determines if the input will be shown on the page
* `filters.*.field` contains options to apply on the input
* `filters.*.field.type` is the input type, it can be one of those: `checkbox`, `date`, `number`, `select`, `text`

### Configuration specific to `select` type:

* `filters.*.field.options` contains the list of the available select options. Here an example:

```php
'options' => [
    [
        'value' => '0',
        'text' => 'Disabled',
    ],
    [
        'value' => '1',
        'text' => 'Enabled',
    ],
    // [ ... ],
],
```

## Filter class

### The `custom_select` definition attribute

Let's say you are defining a `BookFilter` class, and you want to format the number of pages value:

```php
$this->setDefinition('number_of_pages', [
    'custom_select' => 'CONCAT(`number_of_pages`, " pages")',
]);
```

### The `join` definition attribute

Let's say you are defining a `BookFilter` class, and you want to be able to filter on the book's author birth city for example. 

If you want to use a custom select with a join clause, in your Filter class constructor you can do this:

```php
$this->setDefinition('author_birth_city', [
    'join' => 'leftJoinAuthorsTable',
    'custom_select' => '`authors`.`birth_city`',
]);
```

Then, you need to add a method named after your `join` parameter to apply the join on the query, in this example:

```php
protected function leftJoinAuthorsTable(): void
{
    $this->query->leftJoin(
        'authors',
        'books.author_id',
        '=',
        'authors.id'
    );
}
```
