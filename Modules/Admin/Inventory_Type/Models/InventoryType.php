<?php

namespace Modules\Admin\Inventory_Type\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryType extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status'
    ];
}
