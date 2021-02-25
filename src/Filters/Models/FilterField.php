<?php

namespace WebId\Flan\Filters\Models;

use Illuminate\Database\Eloquent\Model;

class FilterField extends Model
{
    /** @var array<string> */
    protected $fillable = [
        'name',
        'content',
    ];

    /** @var array<string> */
    protected $casts = [
        'content' => 'array'
    ];
}
