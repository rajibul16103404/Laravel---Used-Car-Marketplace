<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class validate_phone extends Model
{
    use HasFactory;

    protected $fillable=[
        "phone","otp"
    ];
}
