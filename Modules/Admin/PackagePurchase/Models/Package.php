<?php

namespace Modules\Admin\PackagePurchase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable=[
        'purchase_id', 'user_id', 'car_id', 'package_id', 'purchase_status', 'payment_status'
    ];
}
