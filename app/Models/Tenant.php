<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = ['name', 'code', 'company_name', 'phone', 'email', 'address', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
