<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory;

    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'address',
        'package_id',
        'status',
        'billing_day',
        'joined_at',
        'password',
        'email_notification',
        'whatsapp_notification',
    ];

    protected function casts(): array
    {
        return [
            'status' => CustomerStatus::class,
            'billing_day' => 'integer',
            'joined_at' => 'date',
            'email_notification' => 'boolean',
            'whatsapp_notification' => 'boolean',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function isolirLogs(): HasMany
    {
        return $this->hasMany(IsolirLog::class);
    }

    public function whatsAppNotifications(): HasMany
    {
        return $this->hasMany(WhatsAppNotification::class);
    }

    public function unpaidInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class)
            ->whereIn('status', ['unpaid', 'partial', 'overdue']);
    }

    public function totalOutstanding(): float
    {
        return (float) $this->unpaidInvoices()->get()
            ->sum(fn (Invoice $invoice) => $invoice->remaining());
    }

    public function daysOverdue(): int
    {
        $latestOverdue = $this->unpaidInvoices()
            ->where('due_date', '<', now())
            ->orderByDesc('due_date')
            ->first();

        return $latestOverdue ? now()->diffInDays($latestOverdue->due_date) : 0;
    }
}
