<?php

namespace Modules\Admin\Cylinders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cylinder extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];
}
