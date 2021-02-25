<?php

namespace WebId\Flan\Filters\Services\Dates;

use Carbon\Carbon;

class Date
{
    /**
     * @return Carbon
     */
    public function getCurrentMonthStart(): Carbon
    {
        return Carbon::now()->startOfMonth();
    }

    /**
     * @return Carbon
     */
    public function getCurrentWeekStart(): Carbon
    {
        return Carbon::now()->startOfWeek();
    }

    /**
     * @return Carbon
     */
    public function getPreviousWeekStart(): Carbon
    {
        return Carbon::now()->startOfWeek()->subDays(7);
    }

    /**
     * @return Carbon
     */
    public function getPreviousWeekStop(): Carbon
    {
        return Carbon::now()->startOfWeek()->subDays(1)->setHours(23)->setMinutes(59)->setSeconds(59);
    }

    /**
     * @return Carbon
     */
    public function getPreviousMonthStart(): Carbon
    {
        $lastMonthNumberOfDays = Carbon::now()->startOfMonth()->subDays(1)->daysInMonth;

        return Carbon::now()->startOfMonth()->subDays($lastMonthNumberOfDays);
    }

    /**
     * @return Carbon
     */
    public function getPreviousMonthStop(): Carbon
    {
        return Carbon::now()->startOfMonth()->subDays(1)->setHours(23)->setMinutes(59)->setSeconds(59);
    }
}
