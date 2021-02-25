<?php

namespace WebId\Flan\Filters\Base;

use Illuminate\Database\Query\Builder;

interface FieldContract
{
    /**
     * @param array $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder;

    /**
     * @param string $fieldName
     * @return array
     */
    public static function getRules(string $fieldName): array;
}
