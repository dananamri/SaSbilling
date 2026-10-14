<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\Setting;
use App\Models\WhatsAppNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppGatewayService
{
    public const DEFAULT_ADMIN_PHONE = '+6287863389320';

    public function getAdminPhone(): string
    {
        $phone = Setting::get('admin_wa_phone') ?: Setting::get('admin_whatsapp');

        return (string) ($phone ?: config('services.whatsapp.admin_phone', self::DEFAULT_ADMIN_PHONE));
    }

    public function send(string $phone, string $message, ?int $customerId = null): WhatsAppNotification
    {
        $cleanPhone = $this->formatPhone($phone);

        $notification = WhatsAppNotification::create([
            'customer_id' => $customerId,
            'type' => NotificationType::Info,
            'message' => "To: {$cleanPhone}\n\n{$message}",
            'status' => 'pending',
        ]);

        $token = Setting::get('fonnte_token') ?: config('services.whatsapp.fonnte_token') ?: env('FONNTE_TOKEN');

        if (! empty($token)) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders(['Authorization' => $token])
                    ->post('https://api.fonnte.com/send', [
                        'target' => $cleanPhone,
                        'message' => $message,
                    ]);

                if ($response->successful()) {
                    $notification->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                } else {
                    $notification->update(['status' => 'failed']);
                    Log::warning('Fonnte send failed', ['body' => $response->body()]);
                }
            } catch (\Throwable $e) {
                $notification->update(['status' => 'failed']);
                Log::error('Fonnte exception: '.$e->getMessage());
            }
        } else {
            // Log for dev/local when token not yet configured
            Log::info("WA message queued to {$cleanPhone}: {$message}");
        }

        return $notification;
    }

    public function sendToAdmin(string $message): WhatsAppNotification
    {
        return $this->send($this->getAdminPhone(), $message, null);
    }

    public function generateWaLink(string $phone, string $text): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        }

        return 'https://wa.me/'.$clean.'?text='.rawurlencode($text);
    }

    private function formatPhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($clean, '0')) {
            return '62'.substr($clean, 1);
        }

        return $clean;
    }
}
