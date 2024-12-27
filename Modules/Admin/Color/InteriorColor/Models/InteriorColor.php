<?php

namespace Modules\Admin\Color\InteriorColor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteriorColor extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status'
    ];
}
