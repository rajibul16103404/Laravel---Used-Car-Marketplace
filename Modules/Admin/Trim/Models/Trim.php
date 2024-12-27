<?php

namespace Modules\Admin\Trim\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trim extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
