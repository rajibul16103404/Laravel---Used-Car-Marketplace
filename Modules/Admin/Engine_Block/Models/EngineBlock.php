<?php

namespace Modules\Admin\Engine_Block\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineBlock extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
