<?php

namespace WebId\Flan\Filters\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends Model
{
    /** @var array<string> */
    protected $fillable = [
        'filter_name',
        'label',
    ];

    /** @var array<string> */
    protected $dispatchesEvents = [
        'deleted',
    ];

    /**
     * @return HasMany
     */
    public function fields()
    {
        return $this->hasMany(FilterField::class);
    }
}
