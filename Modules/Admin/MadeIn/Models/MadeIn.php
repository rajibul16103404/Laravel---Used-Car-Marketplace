<?php

namespace Modules\Admin\MadeIn\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MadeIn extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'status'
    ];
}
