<?php

namespace WebId\Flan\Filters\Fields;

use Illuminate\Database\Query\Builder;
use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;

class Text extends Field implements FieldContract
{
    const _STRATEGY_CONTAINS = 'contains';
    const _STRATEGY_IGNORE = 'ignore';

    /** @var array<string> */
    const STRATEGY_TO_OPERATOR = [
        self::_STRATEGY_CONTAINS => 'like',
        self::_STRATEGY_IGNORE => 'not like',
    ];

    /**
     * @param array<string> $search
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
            'search.' . $fieldName => 'array',
            'search.' . $fieldName .'.strategy' => 'required_with:'. $fieldName .'|in:'. $strategyList,
            'search.' . $fieldName .'.term' => 'required_with:'. $fieldName .'|string',
        ];
    }
}
