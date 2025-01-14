<?php

namespace Modules\Admin\Overall_Height\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallHeight extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
