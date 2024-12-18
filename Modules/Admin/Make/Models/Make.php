<?php

namespace Modules\Admin\Make\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];
}
