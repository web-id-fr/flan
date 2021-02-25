<?php

namespace WebId\Flan\Filters\Base;

use Illuminate\Database\Query\Builder;

interface FieldContract
{
    /**
     * @param array<string, mixed> $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder;

    /**
     * @param string $fieldName
     * @return array<string>
     */
    public static function getRules(string $fieldName): array;
}
