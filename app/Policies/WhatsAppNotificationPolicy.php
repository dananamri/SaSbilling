<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Models\WhatsAppNotification;

class WhatsAppNotificationPolicy
{
    public function view(User|Customer $user, WhatsAppNotification $notification): bool
    {
        if ($user instanceof Customer) {
            return $notification->customer_id === $user->id;
        }

        return true;
    }
}
