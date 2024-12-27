<?php

namespace Modules\Admin\Std_seating\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StdSeating extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
