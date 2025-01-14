<?php

namespace Modules\Admin\Engine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
