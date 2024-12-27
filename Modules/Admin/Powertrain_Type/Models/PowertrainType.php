<?php

namespace Modules\Admin\Powertrain_Type\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowertrainType extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
