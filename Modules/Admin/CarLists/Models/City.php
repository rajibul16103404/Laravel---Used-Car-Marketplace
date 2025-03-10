<?php

namespace Modules\Admin\CarLists\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  City extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'country_id',
        'state_code'
    ];
}
