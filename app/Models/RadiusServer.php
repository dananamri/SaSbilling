<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadiusServer extends Model
{
    protected $fillable = ['name', 'host', 'auth_port', 'acct_port', 'secret', 'is_active'];

    protected $casts = ['auth_port' => 'integer', 'acct_port' => 'integer', 'is_active' => 'boolean'];
}
