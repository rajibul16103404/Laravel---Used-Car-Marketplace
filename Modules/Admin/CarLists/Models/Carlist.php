<?php

namespace Modules\Admin\CarLists\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carlist extends Model
{
    use HasFactory;

    protected $fillable=[
        'car_id',
        'vin',
        'heading',
        'price',
        'miles',
        'msrp',
        'vdp_url',
        'carfax_1_owner',
        'carfax_clean_title',
        'exterior_color',
        'interior_color',
        'base_int_color',
        'base_ext_color',
        'dom',
        'dom_180',
        'dom_active',
        'dos_active',
        'seller_type',
        'inventory_type',
        'stock_no',
        'last_seen_at',
        'last_seen_at_date',
        'scraped_at',
        'scraped_at_date',
        'first_seen_at',
        'first_seen_at_date',
        'first_seen_at_source',
        'first_seen_at_source_date',
        'first_seen_at_mc',
        'first_seen_at_mc_date',
        'ref_price',
        'price_change_percent',
        'ref_price_dt',
        'ref_miles',
        'ref_miles_dt',
        'source',
        'in_transit',
        'photo_links',
        'dealer_id',
        'year',
        'make',
        'model',
        'trim',
        'version',
        'body_type',
        'body_subtype',
        'vehicle_type',
        'transmission',
        'drivetrain',
        'fuel_type',
        'engine',
        'engine_size',
        'engine_block',
        'doors',
        'cylinders',
        'made_in',
        'overall_height',
        'overall_length',
        'overall_width',
        'std_seating',
        'highway_mpg',
        'city_mpg',
        'powertrain_type',
        'view_count',
    ];

    // protected $fillable = [
    //     'vin', 'heading', 'price', 'miles', 'msrp', 'data_source', 'vdp_url',
    //     'carfax_1_owner', 'carfax_clean_title', 'exterior_color', 'interior_color',
    //     'base_int_color', 'base_ext_color', 'dom', 'dom_180', 'dom_active',
    //     'dos_active', 'seller_type', 'inventory_type', 'stock_no', 'last_seen_at',
    //     'scraped_at', 'first_seen_at', 'price_change_percent', 'ref_price', 'ref_miles',
    //     'source', 'in_transit', 'media', 'dealer', 'build'
    // ];
}
