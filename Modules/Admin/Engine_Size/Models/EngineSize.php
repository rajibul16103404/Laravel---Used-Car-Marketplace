<?php

namespace Modules\Admin\Engine_Size\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineSize extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
