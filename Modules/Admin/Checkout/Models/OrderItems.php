<?php

namespace Modules\Admin\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable =[
        'order_id', 'items'
    ];

    
}
