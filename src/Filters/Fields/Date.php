<?php

namespace WebId\Flan\Filters\Fields;

use Illuminate\Database\Query\Builder;
use WebId\Flan\Filters\Base\Field;
use WebId\Flan\Filters\Base\FieldContract;
use WebId\Flan\Filters\Services\Dates\Date as DateService;

class Date extends Field implements FieldContract
{
    const _STRATEGY_EQUALS = 'equals';
    const _STRATEGY_BETWEEN = 'between';
    const _STRATEGY_CURRENT_WEEK = 'current_week';
    const _STRATEGY_PAST_WEEK = 'past_week';
    const _STRATEGY_CURRENT_MONTH = 'current_month';
    const _STRATEGY_PAST_MONTH = 'past_month';

    /** @var array<string> */
    const STRATEGIES = [
        self::_STRATEGY_EQUALS,
        self::_STRATEGY_BETWEEN,
        self::_STRATEGY_CURRENT_WEEK,
        self::_STRATEGY_PAST_WEEK,
        self::_STRATEGY_CURRENT_MONTH,
        self::_STRATEGY_PAST_MONTH,
    ];

    /** @var DateService */
    protected $dateService;

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        parent::__construct($query);

        $this->dateService = app(DateService::class);
    }

    /**
     * @param array<string> $search
     * @param string $columnName
     * @return Builder
     */
    public function query(array $search, string $columnName): Builder
    {
        switch ($search['strategy']) {
            case self::_STRATEGY_BETWEEN:
                return $this->query->whereRaw("$columnName between ? and ?", [
                    $search['date'] . ' 00:00:00',
                    $search['second_date'] . ' 23:59:59',
                ]);

            case self::_STRATEGY_CURRENT_WEEK:
                return $this->query->whereRaw("$columnName >= ?", [
                    $this->dateService->getCurrentWeekStart()->format(config('flan.default_date_format_input')),
                ]);

            case self::_STRATEGY_PAST_WEEK:
                return $this->query->whereRaw("$columnName between ? and ?", [
                    $this->dateService->getPreviousWeekStart()->format(config('flan.default_date_format_input')),
                    $this->dateService->getPreviousWeekStop()->format(config('flan.default_date_format_input')),
                ]);

            case self::_STRATEGY_CURRENT_MONTH:
                return $this->query->whereRaw("$columnName >= ?", [
                    $this->dateService->getCurrentMonthStart()->format(config('flan.default_date_format_input')),
                ]);

            case self::_STRATEGY_PAST_MONTH:
                return $this->query->whereRaw("$columnName between ? and ?", [
                    $this->dateService->getPreviousMonthStart()->format(config('flan.default_date_format_input')),
                    $this->dateService->getPreviousMonthStop()->format(config('flan.default_date_format_input')),
                ]);
        }

        return $this->query->whereRaw("$columnName like ?", [$search['date'].'%']);
    }

    /**
     * @param string $fieldName
     * @return array<string, string>
     */
    public static function getRules(string $fieldName): array
    {
        $strategyList = implode(',', self::STRATEGIES);

        return [
            'search.' . $fieldName => 'array',
            'search.' . $fieldName . '.strategy' => 'required_with:' . $fieldName . '|string|in:' . $strategyList,
            'search.' . $fieldName . '.date' => 'nullable|required_if:' . $fieldName . '.strategy,equals|date_format:Y-m-d',
            'search.' . $fieldName . '.second_date' => 'nullable|required_if:' . $fieldName . '.strategy,between|date_format:Y-m-d',
        ];
    }
}
