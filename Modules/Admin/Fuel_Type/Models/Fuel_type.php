<?php

namespace Modules\Admin\Fuel_Type\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuel_type extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];
}
