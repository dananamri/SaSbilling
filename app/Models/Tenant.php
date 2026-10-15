<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = ['name', 'code', 'company_name', 'phone', 'email', 'address', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    // Core
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Network
    public function routers(): HasMany
    {
        return $this->hasMany(Router::class);
    }

    public function olts(): HasMany
    {
        return $this->hasMany(Olt::class);
    }

    public function odps(): HasMany
    {
        return $this->hasMany(Odp::class);
    }

    // Tickets & Billing
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // PPPoE
    public function pppoeProfiles(): HasMany
    {
        return $this->hasMany(PppoeProfile::class);
    }

    public function pppoeSecrets(): HasMany
    {
        return $this->hasMany(PppoeSecret::class);
    }

    public function routerBoxes(): HasMany
    {
        return $this->hasMany(RouterBox::class);
    }

    // Monitoring
    public function bandwidthSnapshots(): HasMany
    {
        return $this->hasMany(BandwidthSnapshot::class);
    }

    public function radiusServers(): HasMany
    {
        return $this->hasMany(RadiusServer::class);
    }

    // Fiber/GIS
    public function onus(): HasMany
    {
        return $this->hasMany(Onu::class);
    }

    public function acsDevices(): HasMany
    {
        return $this->hasMany(AcsDevice::class);
    }

    // Financial
    public function bookEntries(): HasMany
    {
        return $this->hasMany(BookEntry::class);
    }

    public function cashDeposits(): HasMany
    {
        return $this->hasMany(CashDeposit::class);
    }

    public function paymentTickets(): HasMany
    {
        return $this->hasMany(PaymentTicket::class);
    }

    // HR
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // Others
    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(WhatsAppNotification::class);
    }

    public function isolirLogs(): HasMany
    {
        return $this->hasMany(IsolirLog::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
