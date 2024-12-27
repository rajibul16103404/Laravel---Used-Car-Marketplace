<?php

namespace Modules\Admin\Vehicle_Type\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
