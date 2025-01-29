<?php

namespace Modules\Admin\SpotlightPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spotlight extends Model
{
    use HasFactory;

    protected $fillable=[
        'package_name', 'duration', 'price', 'status'
    ];
}
