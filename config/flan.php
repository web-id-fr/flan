<?php

use WebId\Flan\Filters\Fields\Checkbox;
use WebId\Flan\Filters\Fields\Date;
use WebId\Flan\Filters\Fields\Number;
use WebId\Flan\Filters\Fields\Select;
use WebId\Flan\Filters\Fields\Text;

return [
    'filter_class_directory' => 'FilterClasses',
    'filter_config_directory' => 'FilterConfigs',
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
];
