<?php

namespace WebId\Flan\Filters\Base;

use WebId\Flan\Filters\Fields\Checkbox;
use WebId\Flan\Filters\Fields\Date;
use WebId\Flan\Filters\Fields\Number;
use WebId\Flan\Filters\Fields\Select;
use WebId\Flan\Filters\Fields\Text;
use Illuminate\Database\Query\Builder;

class FieldFactory
{
    /**
     * @param string $type
     * @param Builder $query
     * @return FieldContract
     * @throws \InvalidArgumentException
     */
    public static function create(string $type, Builder $query): FieldContract
    {
        $class = self::getClass($type);

        return new $class($query);
    }

    /**
     * @param string $type
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getClass(string $type): string
    {
        $fieldTypes = config('flan.field_classes');

        if (isset($fieldTypes[$type])) {
            return $fieldTypes[$type];
        }

        throw new \InvalidArgumentException("FieldContract implementation for '$type' doesn't exist");
    }
}
