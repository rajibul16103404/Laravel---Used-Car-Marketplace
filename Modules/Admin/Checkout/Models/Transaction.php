<?php

namespace Modules\Admin\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $filalble=[
        'user'
    ];
}
