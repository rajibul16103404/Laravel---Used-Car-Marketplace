<?php

namespace Modules\Admin\CarLists\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carlist extends Model
{
    use HasFactory;

    protected $fillable=[
        'seller_id',
        'title',
        'make_id',
        'model_id',
        'body_type_id',
        'drive_type_id',
        'transmission_id',
        'condition_id',
        'years',
        'fuel_type_id',
        'engine_size',
        'door_id',
        'cylinder_id',
        'color_id',
        'description',
        'price',
        'safety_features',
        'key_features',
        'category_id',
        'imageURL',
        'videoURL',
    ];
}
