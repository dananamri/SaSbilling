<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();
        $tickets = Ticket::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('customer.tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|in:internet_mati,internet_lambat,gangguan_jaringan,masalah_pembayaran,masalah_paket,perubahan_data,lainnya',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $customer = auth('customer')->user();

        $ticket = Ticket::create([
            'ticket_number' => 'TCK-'.strtoupper(Str::random(6)),
            'customer_id' => $customer->id,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        return redirect()->route('customer.tickets.show', $ticket)->with('success', 'Pengaduan berhasil dibuat. Nomor tiket: '.$ticket->ticket_number);
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['messages.user']);

        return view('customer.tickets.show', compact('ticket'));
    }
}
