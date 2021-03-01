<?php

use WebId\Flan\Filters\Fields\Checkbox;
use WebId\Flan\Filters\Fields\Date;
use WebId\Flan\Filters\Fields\Number;
use WebId\Flan\Filters\Fields\Select;
use WebId\Flan\Filters\Fields\Text;

return [
    'filter_class_directory' => app_path('FilterClasses'), //Directory for Filter Classes (ex: UserFilter)
    'filter_config_directory' => config_path('FilterConfigs'), //Directory for filter config (ex: users)
    'default_model_namespace' => 'App\\Models', //Default model namespace used
    'default_filter_class_namespace' => 'App\\FilterClasses', //Default filter class namespace according with "filter_class_directory"
    'field_classes' => [
        'text'          => Text::class,
        'number'        => Number::class,
        'checkbox'      => Checkbox::class,
        'select'        => Select::class,
        'date'          => Date::class,
        'datetime'      => Date::class,
    ],
    'default_date_format_input' => 'Y-m-d H:i:s', //Default date column format (ex: created_at)
    'default_sql_date_format_output' => '%Y-%m-%d', //Default filter date type format
    'default_sql_datetime_format_output' => '%Y-%m-%d at %Hh%i', //Default filter datetime type format
    'routing' => [
        'filters' => [
            'active' => true, //active or not filters routes
            'middlewares' => [
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],
            'prefix' => 'filters',
            'name' => 'filters.'
        ],
        'export' => [
            'active' => true, //active or not export route
            'middlewares' => [
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],
            'prefix' => 'filters',
            'name' => 'filters.'
        ]
    ],
];
