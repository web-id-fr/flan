<?php

namespace WebId\Flan\Filters\Fields;

use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;
use Illuminate\Database\Query\Builder;

class Text extends Field implements FieldContract
{
    const _STRATEGY_CONTAINS = 'contains';
    const _STRATEGY_IGNORE = 'ignore';

    /** @var array */
    const STRATEGY_TO_OPERATOR = [
        self::_STRATEGY_CONTAINS => 'like',
        self::_STRATEGY_IGNORE   => 'not like',
    ];

    /**
     * @param array $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder
    {
        $operator = self::STRATEGY_TO_OPERATOR[$search['strategy']];

        return $this->query->whereRaw("$columnName $operator ?", ['%'.$search['term'].'%']);
    }

    /**
     * @param string $fieldName
     * @return array<string, string>
     */
    public static function getRules(string $fieldName): array
    {
        $strategyList = implode(',', array_keys(self::STRATEGY_TO_OPERATOR));

        return [
            $fieldName => 'array',
            $fieldName .'.strategy' => 'required_with:'. $fieldName .'|in:'. $strategyList,
            $fieldName .'.term' => 'required_with:'. $fieldName .'|string'
        ];
    }
}
