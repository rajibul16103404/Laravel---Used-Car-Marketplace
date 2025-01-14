<?php

namespace Modules\Admin\DriveTrain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriveTrain extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}