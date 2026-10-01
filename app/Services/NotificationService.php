<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\WhatsAppNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendReminder(Customer $customer, Invoice $invoice): WhatsAppNotification
    {
        $message = $this->buildReminderMessage($customer, $invoice);

        return $this->createNotification($customer, NotificationType::Reminder, $message);
    }

    public function sendPaymentReceived(Customer $customer, Invoice $invoice, float $amount): WhatsAppNotification
    {
        $message = "Halo {$customer->name},\n\n"
            .'Pembayaran Anda sebesar Rp '.number_format($amount, 0, ',', '.')." telah diterima.\n"
            ."Invoice: {$invoice->invoice_number}\n"
            .'Terima kasih!';

        return $this->createNotification($customer, NotificationType::PaymentReceived, $message);
    }

    public function sendIsolatedNotification(Customer $customer): WhatsAppNotification
    {
        $message = "Halo {$customer->name},\n\n"
            ."Layanan Anda telah diisolir karena tunggakan melebihi batas waktu.\n"
            .'Silakan hubungi admin untuk informasi lebih lanjut.';

        return $this->createNotification($customer, NotificationType::Isolated, $message);
    }

    public function sendReopenedNotification(Customer $customer): WhatsAppNotification
    {
        $message = "Halo {$customer->name},\n\n"
            .'Layanan Anda telah aktif kembali. Terima kasih atas pembayarannya.';

        return $this->createNotification($customer, NotificationType::Reopened, $message);
    }

    public function sendInfo(Customer $customer, string $message): WhatsAppNotification
    {
        return $this->createNotification($customer, NotificationType::Info, $message);
    }

    public function sendInvoiceNotification(Customer $customer, Invoice $invoice): WhatsAppNotification
    {
        $message = "Halo {$customer->name},\n\n"
            ."Tagihan baru telah diterbitkan untuk Anda.\n"
            ."Invoice: {$invoice->invoice_number}\n"
            ."Periode: {$invoice->period}\n"
            .'Jumlah: Rp '.number_format($invoice->amount, 0, ',', '.')."\n"
            .'Jatuh tempo: '.$invoice->due_date->format('d F Y')."\n\n"
            .'Mohon segera lakukan pembayaran sebelum jatuh tempo. Terima kasih!';

        return $this->createNotification($customer, NotificationType::Info, $message);
    }

    public function markAsSent(WhatsAppNotification $notification): void
    {
        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(WhatsAppNotification $notification, string $reason = ''): void
    {
        $notification->update([
            'status' => 'failed',
        ]);

        Log::error("Notification {$notification->id} failed: {$reason}");
    }

    private function buildReminderMessage(Customer $customer, Invoice $invoice): string
    {
        $remaining = $invoice->remaining();

        return "Halo {$customer->name},\n\n"
            ."Ini adalah pengingat tagihan Anda.\n"
            ."Invoice: {$invoice->invoice_number}\n"
            ."Periode: {$invoice->period}\n"
            .'Jumlah: Rp '.number_format($remaining, 0, ',', '.')."\n"
            .'Jatuh tempo: '.$invoice->due_date->format('d F Y')."\n\n"
            .'Mohon segera lakukan pembayaran. Terima kasih!';
    }

    private function createNotification(Customer $customer, NotificationType $type, string $message): WhatsAppNotification
    {
        return WhatsAppNotification::create([
            'customer_id' => $customer->id,
            'type' => $type,
            'message' => $message,
            'status' => 'pending',
        ]);
    }
}
