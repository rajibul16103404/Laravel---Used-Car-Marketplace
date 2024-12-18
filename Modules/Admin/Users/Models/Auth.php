<?php

namespace Modules\Admin\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Auth extends Model
{
    use HasFactory, HasApiTokens;

    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'address',
        'city',
        'zip',
        'country',
        'company_name',
        'company_address',
        'company_email',
        'company_phone',
        'imageURL',
        'password',
        'role',

    ];

    
}
