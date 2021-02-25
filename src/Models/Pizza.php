<?php

namespace WebId\Flan\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;

    const ACTIVE_LIST = [
        '0' => 'unactive',
        '1' => 'active',
    ];
}
