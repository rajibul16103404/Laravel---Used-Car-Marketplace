<?php

namespace Modules\Admin\Body_Type\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Body_Type extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'status'
    ];
}
