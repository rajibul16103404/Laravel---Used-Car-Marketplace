<?php

namespace Modules\Admin\Seller_Type\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerType extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status'
    ];
}
