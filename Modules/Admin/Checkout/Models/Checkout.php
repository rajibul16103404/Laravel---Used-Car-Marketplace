<?php

namespace Modules\Admin\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\CarLists\Models\Carlist;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable=[
        'order_id','amount','user_id','status'
    ];

    public function carlist()
    {
        return $this->belongsTo(Carlist::class, 'carlist_id'); // Adjust 'carlist_id' if your foreign key is named differently
    }


}
