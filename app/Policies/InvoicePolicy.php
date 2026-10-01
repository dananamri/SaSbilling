<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function view(User|Customer $user, Invoice $invoice): bool
    {
        if ($user instanceof Customer) {
            return $invoice->customer_id === $user->id;
        }

        return true;
    }
}
