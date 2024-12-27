<?php

namespace Modules\Admin\Highway_Mpg\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighwayMpg extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
