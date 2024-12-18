<?php

namespace Modules\Admin\CarModel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carmodel extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];
}
