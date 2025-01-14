<?php

namespace Modules\Admin\Body_Subtype\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodySubType extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'status'
    ];
}
