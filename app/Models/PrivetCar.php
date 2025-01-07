<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivetCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'vin', 'heading', 'price', 'miles', 'msrp', 'data_source', 'vdp_url',
        'carfax_1_owner', 'carfax_clean_title', 'exterior_color', 'interior_color',
        'base_int_color', 'base_ext_color', 'dom', 'dom_180', 'dom_active',
        'dos_active', 'seller_type', 'inventory_type', 'stock_no', 'last_seen_at',
        'scraped_at', 'first_seen_at', 'price_change_percent', 'ref_price', 'ref_miles',
        'source', 'in_transit', 'media', 'dealer', 'build'
    ];
    
}
