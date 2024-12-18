<?php

namespace Modules\Admin\Doors\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Door extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];
}
