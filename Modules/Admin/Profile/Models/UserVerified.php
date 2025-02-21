<?php

namespace Modules\Admin\Profile\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerified extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id', 'verification_id', 'photo_id', 'address_doc', 'business_doc', 'payment_status', 'status'
    ];
}
