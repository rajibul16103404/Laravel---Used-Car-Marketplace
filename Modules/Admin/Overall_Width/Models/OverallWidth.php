<?php

namespace Modules\Admin\Overall_Width\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallWidth extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
