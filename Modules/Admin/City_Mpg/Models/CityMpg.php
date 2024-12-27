<?php

namespace Modules\Admin\City_Mpg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityMpg extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
