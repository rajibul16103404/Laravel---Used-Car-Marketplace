<?php

namespace Modules\Admin\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\CarLists\Models\Carlist;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable=[
        'order_id', 'order_from', 'car_id', 'amount','user_id','full_name','phone','street','city','state','zip','country_code', 'port_code','order_status','payment_status','delivery_status', 'shipping_fee', 'platform_fee', 'total'
    ];

    public function carlist()
    {
        return $this->belongsTo(Carlist::class, 'carlist_id'); // Adjust 'carlist_id' if your foreign key is named differently
    }


}
