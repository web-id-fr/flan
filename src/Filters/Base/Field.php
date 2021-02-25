<?php

namespace WebId\Flan\Filters\Base;

use Illuminate\Database\Query\Builder;

abstract class Field
{
    /** @var Builder */
    protected $query;

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }
}
