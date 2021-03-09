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
