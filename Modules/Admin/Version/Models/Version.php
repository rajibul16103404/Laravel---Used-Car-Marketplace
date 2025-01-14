<?php

namespace Modules\Admin\Version\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
