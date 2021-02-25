<?php

namespace WebId\Flan\Filters\Fields;

use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;
use Illuminate\Database\Query\Builder;

class Select extends Field implements FieldContract
{
    /**
     * @param array $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder
    {
        return $this->query->whereRaw("$columnName = ?", [$search['term']]);
    }

    /**
     * @param string $fieldName
     * @return array
     */
    public static function getRules(string $fieldName): array
    {
        return [
            $fieldName => 'array',
            $fieldName .'.term' => 'nullable|string',
        ];
    }
}
