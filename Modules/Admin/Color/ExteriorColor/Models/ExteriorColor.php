<?php

namespace Modules\Admin\Color\ExteriorColor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExteriorColor extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status'
    ];
}
