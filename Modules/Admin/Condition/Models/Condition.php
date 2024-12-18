<?php

namespace Modules\Admin\Condition\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];
}
