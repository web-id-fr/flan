<?php

namespace WebId\Flan\Filters\Fields;

use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;
use Illuminate\Database\Query\Builder;

class Checkbox extends Field implements FieldContract
{
    /**
     * @param array<string> $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder
    {
        return $this->query->where(function ($q) use ($columnName, $search) {
            foreach ($search as $term) {
                $q->orWhereRaw("$columnName = ?", [$term]);
            }
        });
    }

    /**
     * @param string $fieldName
     * @return array<string, string>
     */
    public static function getRules(string $fieldName): array
    {
        return [
            $fieldName => 'array',
            $fieldName .'.*' => 'string'
        ];
    }
}
