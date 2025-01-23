<?php

namespace Modules\Admin\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable=[
        'transaction_id',
        'payment_id',
        'amount',
        'currency',
        'status',
        'order_from',
        'order_id',
        'car_id'
    ];
}
