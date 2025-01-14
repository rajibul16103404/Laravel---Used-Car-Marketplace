<?php

namespace Modules\Admin\Overall_Length\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallLength extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
