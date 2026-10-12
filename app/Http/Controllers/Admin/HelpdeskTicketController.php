<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HelpdeskTicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = Ticket::with(['customer', 'technician'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->technician_id, fn ($q) => $q->where('assigned_to', $request->technician_id))
            ->orderByDesc('created_at')
            ->paginate(15);

        $technicians = User::whereIn('role', ['teknisi', 'noc', 'admin'])->orderBy('name')->get();

        return view('admin.tickets.index', compact('tickets', 'technicians'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load(['customer.package', 'technician', 'messages']);
        $technicians = User::whereIn('role', ['teknisi', 'noc', 'admin'])->orderBy('name')->get();

        return view('admin.tickets.show', compact('ticket', 'technicians'));
    }

    public function assign(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $slaHours = match ($validated['priority']) {
            'urgent' => 4,
            'high' => 8,
            'medium' => 24,
            default => 48,
        };

        $ticket->update([
            'assigned_to' => $validated['assigned_to'],
            'priority' => $validated['priority'],
            'status' => 'in_progress',
            'sla_due_at' => now()->addHours($slaHours),
        ]);
        ActivityLog::log('updated', 'gangguan', "Assign tiket #{$ticket->ticket_number} ke teknisi ID {$validated['assigned_to']}");

        return back()->with('success', 'Tiket ditugaskan ke teknisi & SLA dihitung.');
    }

    public function resolve(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string',
            'proof_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('proof_photo')) {
            $validated['proof_photo'] = $request->file('proof_photo')->store('ticket_proofs', 'public');
        }

        $ticket->update([
            'resolution_notes' => $validated['resolution_notes'],
            'proof_photo' => $validated['proof_photo'] ?? $ticket->proof_photo,
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
        ActivityLog::log('resolved', 'gangguan', "Resolusi tiket #{$ticket->ticket_number}");

        return back()->with('success', 'Tiket ditandai selesai / resolved.');
    }
}
