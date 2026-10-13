<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'customer_id',
        'current_package_id',
        'target_package_id',
        'status',
        'notes',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function currentPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'current_package_id');
    }

    public function targetPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'target_package_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->request_number)) {
                $m->request_number = 'UPG-'.date('Ymd').'-'.str_pad((string) (self::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
