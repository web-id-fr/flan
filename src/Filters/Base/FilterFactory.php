<?php

namespace WebId\Flan\Filters\Base;

use WebId\Flan\Filters\Services\MagicCollector;

class FilterFactory
{
    /**
     * @param string $type
     * @return Filter
     */
    public static function create(string $type): Filter
    {
        return app(self::getClass($type));
    }

    /**
     * @param string $type
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getClass(string $type): string
    {
        $filterClasses = MagicCollector::getClasses();

        if (isset($filterClasses[$type])) {
            return $filterClasses[$type];
        }

        throw new \InvalidArgumentException("Filter named '$type' doesn't exist");
    }
}
