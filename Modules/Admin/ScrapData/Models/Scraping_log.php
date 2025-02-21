<?php

namespace Modules\Admin\ScrapData\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scraping_log extends Model
{
    use HasFactory;

    protected $fillable=[
        'scrape_date',
        'base_url',
        'page',
        'url',
        'num_data',
        'status'
    ];
}
