<?php

namespace Modules\Admin\SpotlightPackage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable=[
        'purchase_id', 'user_id', 'car_id', 'promotion_name', 'package_id', 'amount', 'purchase_status', 'payment_status'
    ];
}
