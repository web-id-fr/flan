<?php

use WebId\Flan\Filters\Fields\Checkbox;
use WebId\Flan\Filters\Fields\Date;
use WebId\Flan\Filters\Fields\Number;
use WebId\Flan\Filters\Fields\Select;
use WebId\Flan\Filters\Fields\Text;

return [
    'filter_class_directory' => app_path('FilterClasses'),
    'filter_config_directory' => config_path('FilterConfigs'),
    'default_model_namespace' => 'App\\Models',
    'default_filter_class_namespace' => 'App\\FilterClasses',
    'field_classes' => [
        'text'          => Text::class,
        'number'        => Number::class,
        'checkbox'      => Checkbox::class,
        'select'        => Select::class,
        'date'          => Date::class,
        'datetime'      => Date::class,
    ],
    'default_date_format_input' => 'Y-m-d H:i:s',
    'default_sql_date_format_output' => '%Y-%m-%d',
    'default_sql_datetime_format_output' => '%Y-%m-%d at %Hh%i',
    'routing' => [
        'filters' => [
            'active' => true,
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
            'active' => true,
            'middlewares' => [],
            'prefix' => 'filters',
            'name' => 'filters.'
        ]
    ],
];
