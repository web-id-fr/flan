<?php

namespace WebId\Flan\Filters\Fields;

use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;
use Illuminate\Database\Query\Builder;

class Number extends Field implements FieldContract
{
    const _STRATEGY_MATCH = 'equals';
    const _STRATEGY_BETWEEN = 'between';
    const _STRATEGY_BIGGER = 'bigger';
    const _STRATEGY_LOWER = 'lower';
    const _STRATEGY_NOT_IN = 'not_in';
    const _STRATEGY_IS_NULL = 'is_null';

    /** @var array */
    const STRATEGIES = [
        self::_STRATEGY_MATCH,
        self::_STRATEGY_BETWEEN,
        self::_STRATEGY_BIGGER,
        self::_STRATEGY_LOWER,
        self::_STRATEGY_NOT_IN,
        self::_STRATEGY_IS_NULL,
    ];

    /** @var array */
    const STRATEGIES_TO_OPERATOR = [
        self::_STRATEGY_MATCH   => '=',
        self::_STRATEGY_BIGGER  => '>=',
        self::_STRATEGY_LOWER   => '<=',
    ];

    const STRATEGIES_WITHOUT_TERM = [
        self::_STRATEGY_IS_NULL
    ];

    /**
     * @param array $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder
    {
        if ($search['strategy'] === self::_STRATEGY_BETWEEN) {
            return $this->query
                ->whereRaw("$columnName between ? and ?", [
                    $search['term'],
                    $search['second_term']
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
            $fieldName => 'array',
            $fieldName .'.strategy' => 'required_with:'. $fieldName .'|in:'. $strategyList,
            $fieldName .'.second_term' => 'nullable|required_if:'. $fieldName .'.select,between|numeric',
            $fieldName .'.term' => [
                'nullable',
                'required_if:'. $fieldName . '.strategy,' . $strategyListRequiringTerm,
                'integer_if:' . $fieldName .'.strategy,equals,between,bigger,lower,different',
                'array_if:' . $fieldName .'.strategy,not_in',
            ],
            $fieldName .'.term.*' => 'nullable|integer'
        ];
    }
}
