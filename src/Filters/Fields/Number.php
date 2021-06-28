<?php

namespace WebId\Flan\Filters\Fields;

use Illuminate\Database\Query\Builder;
use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;

class Number extends Field implements FieldContract
{
    const _STRATEGY_MATCH = 'equals';
    const _STRATEGY_BETWEEN = 'between';
    const _STRATEGY_BIGGER = 'bigger';
    const _STRATEGY_LOWER = 'lower';
    const _STRATEGY_NOT_IN = 'not_in';
    const _STRATEGY_IS_NULL = 'is_null';

    /** @var array<string> */
    const STRATEGIES = [
        self::_STRATEGY_MATCH,
        self::_STRATEGY_BETWEEN,
        self::_STRATEGY_BIGGER,
        self::_STRATEGY_LOWER,
        self::_STRATEGY_NOT_IN,
        self::_STRATEGY_IS_NULL,
    ];

    /** @var array<string> */
    const STRATEGIES_TO_OPERATOR = [
        self::_STRATEGY_MATCH => '=',
        self::_STRATEGY_BIGGER => '>=',
        self::_STRATEGY_LOWER => '<=',
    ];

    const STRATEGIES_WITHOUT_TERM = [
        self::_STRATEGY_IS_NULL,
    ];

    /**
     * @param array<string, mixed> $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder
    {
        if ($search['strategy'] === self::_STRATEGY_BETWEEN) {
            return $this->query
                ->whereRaw("$columnName between ? and ?", [
                    $search['term'],
                    $search['second_term'],
                ]);
        }

        if ($search['strategy'] === self::_STRATEGY_NOT_IN) {
            $queryString = implode(',', array_fill(0, count($search['term']), '?'));

            return $this->query
                ->whereRaw("$columnName NOT IN ($queryString)", $search['term']);
        }

        if ($search['strategy'] === self::_STRATEGY_IS_NULL) {
            return $this->query
                ->whereRaw("$columnName IS NULL");
        }

        $operator = self::STRATEGIES_TO_OPERATOR[$search['strategy']];

        return $this->query->whereRaw("$columnName $operator ?", [$search['term']]);
    }

    /**
     * @param string $fieldName
     * @return array<string, array<int, string>|string>
     */
    public static function getRules(string $fieldName): array
    {
        $strategyList = implode(',', self::STRATEGIES);
        $strategyListRequiringTerm = implode(',', array_diff(self::STRATEGIES, self::STRATEGIES_WITHOUT_TERM));

        return [
            'search.' . $fieldName => 'array',
            'search.' . $fieldName .'.strategy' => 'required_with:'. $fieldName .'|in:'. $strategyList,
            'search.' . $fieldName .'.second_term' => 'nullable|required_if:'. $fieldName .'.select,between|numeric',
            'search.' . $fieldName .'.term' => [
                'nullable',
                'required_if:'. $fieldName . '.strategy,' . $strategyListRequiringTerm,
                'flan_integer_if:' . $fieldName .'.strategy,equals,between,bigger,lower,different',
                'flan_array_if:' . $fieldName .'.strategy,not_in',
            ],
            'search.' . $fieldName .'.term.*' => 'nullable|integer',
        ];
    }
}
