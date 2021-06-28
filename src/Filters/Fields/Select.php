<?php

namespace WebId\Flan\Filters\Fields;

use Illuminate\Database\Query\Builder;
use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;

class Select extends Field implements FieldContract
{
    /**
     * @param array<string> $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder
    {
        return $this->query->whereRaw("$columnName = ?", [$search['term']]);
    }

    /**
     * @param string $fieldName
     * @return array<string>
     */
    public static function getRules(string $fieldName): array
    {
        return [
            'search.' . $fieldName => 'array',
            'search.' . $fieldName .'.term' => 'nullable|string',
        ];
    }
}
