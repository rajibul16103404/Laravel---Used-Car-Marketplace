<?php

namespace Modules\Admin\CartItem\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'country', 'country_code', 'port', 'port_code', 'amount', 'status'
    ];
}
