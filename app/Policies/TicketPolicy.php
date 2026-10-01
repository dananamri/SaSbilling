<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User|Customer $user, Ticket $ticket): bool
    {
        if ($user instanceof Customer) {
            return $ticket->customer_id === $user->id;
        }

        return true;
    }

    public function create(User|Customer $user): bool
    {
        return $user instanceof Customer;
    }
}
