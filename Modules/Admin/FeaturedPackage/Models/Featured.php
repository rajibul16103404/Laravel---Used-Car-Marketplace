<?php

namespace Modules\Admin\FeaturedPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Featured extends Model
{
    use HasFactory;

    protected $fillable=[
        'package_name', 'duration', 'price', 'status'
    ]; 
}
