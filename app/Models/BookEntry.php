<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookEntry extends Model
{
    protected $fillable = ['entry_number', 'type', 'category', 'amount', 'date', 'description', 'receipt_path', 'user_id', 'tenant_id'];

    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->entry_number)) {
                $m->entry_number = 'BK-'.date('Ymd').'-'.str_pad((string) (self::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
