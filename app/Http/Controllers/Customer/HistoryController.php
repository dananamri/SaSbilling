<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(Request $request): View
    {
        $customer = auth('customer')->user();
        $tab = $request->query('tab', 'invoices');

        $invoices = Invoice::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'invoices_page');

        $payments = Payment::whereHas('invoice', fn ($q) => $q->where('customer_id', $customer->id))
            ->with('invoice')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'payments_page');

        $tickets = Ticket::where('customer_id', $customer->id)
            ->with('messages')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'tickets_page');

        return view('customer.history.index', compact('customer', 'tab', 'invoices', 'payments', 'tickets'));
    }
}
