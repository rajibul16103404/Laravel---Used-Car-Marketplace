<?php

namespace Modules\Admin\CartItem\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\CarLists\Models\Carlist;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = ['user_id', 'carlist_id'];

    public function carlist()
    {
        return $this->belongsTo(Carlist::class);
    }
}
