<?php

namespace App\Models;

use App\Support\AdminPermissions;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['role', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => AdminPermissions::flushCache());
        static::deleted(fn () => AdminPermissions::flushCache());
    }
}
