<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function view(User|Customer $user, Payment $payment): bool
    {
        if ($user instanceof Customer) {
            return $payment->invoice->customer_id === $user->id;
        }

        return true;
    }
}
