<?php

namespace WebId\Flan\Filters\Base;

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
