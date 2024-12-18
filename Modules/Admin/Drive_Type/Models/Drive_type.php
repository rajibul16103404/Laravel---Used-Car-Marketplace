<?php

namespace Modules\Admin\Drive_Type\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drive_type extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];
}
