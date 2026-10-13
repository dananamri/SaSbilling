<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected WhatsAppGatewayService $waGateway,
    ) {}

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
            'ticket_number' => 'TCK-'.date('Ymd').'-'.strtoupper(Str::random(4)),
            'customer_id' => $customer->id,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        // Format pesan notifikasi ke WhatsApp Admin (+6287863389320)
        $categoryLabel = str_replace('_', ' ', ucwords($validated['category'], '_'));
        $adminMessage = "🚨 *PENGADUAN GANGGUAN BARU* 🚨\n\n"
            ."*No Tiket:* {$ticket->ticket_number}\n"
            ."*Pelanggan:* {$customer->name} (ID: CUST-".str_pad((string) $customer->id, 4, '0', STR_PAD_LEFT).")\n"
            ."*No HP:* {$customer->phone}\n"
            ."*Kategori:* {$categoryLabel}\n"
            .'*Prioritas:* '.strtoupper($validated['priority'])."\n"
            ."*Judul:* {$validated['title']}\n"
            ."*Deskripsi:* {$validated['description']}\n"
            .'*Alamat:* '.($customer->address ?: '-')."\n"
            .'*Waktu:* '.now()->format('d-m-Y H:i')." WIB\n\n"
            .'Mohon segera ditindaklanjuti oleh tim teknis.';

        $this->waGateway->sendToAdmin($adminMessage);

        return redirect()->route('customer.tickets.show', $ticket)
            ->with('success', 'Laporan gangguan berhasil dibuat dan diteruskan ke WhatsApp Admin. Nomor tiket: '.$ticket->ticket_number);
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['messages.user', 'customer']);

        $adminPhone = $this->waGateway->getAdminPhone();
        $waText = "Halo Admin SATAK, saya {$ticket->customer->name} ingin menanyakan progres tiket gangguan saya #{$ticket->ticket_number}: {$ticket->title}";
        $directWaUrl = $this->waGateway->generateWaLink($adminPhone, $waText);

        return view('customer.tickets.show', compact('ticket', 'directWaUrl', 'adminPhone'));
    }
}
