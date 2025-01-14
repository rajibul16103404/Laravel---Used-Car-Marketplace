<?php

namespace Modules\Admin\Year\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status'
    ];
}
