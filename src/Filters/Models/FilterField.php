<?php

namespace WebId\Flan\Filters\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $content
 */
class FilterField extends Model
{
    /** @var array<string> */
    protected $fillable = [
        'name',
        'content',
    ];

    /** @var array<string> */
    protected $casts = [
        'content' => 'array',
    ];
}
