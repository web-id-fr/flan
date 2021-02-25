<?php

namespace WebId\Flan\Facades;

use Illuminate\Support\Facades\Facade;

class Flan extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'flan';
    }
}
