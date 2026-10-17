<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\IsolirLog;
use App\Models\PackageChangeRequest;
use App\Models\Payment;
use App\Models\PaymentTicket;
use App\Models\Ticket;
use App\Models\WhatsAppNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanCustomersCommand extends Command
{
    protected $signature = 'billing:clean-customers {--force : Jalankan pembersihan tanpa konfirmasi interaktif}';

    protected $description = 'Menghapus seluruh data pelanggan beserta tagihan, pembayaran, tiket dan riwayat terkait.';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Hapus SEMUA data pelanggan, tagihan, pembayaran, dan tiket? Tidak dapat dibatalkan!')) {
            $this->info('Pembersihan dibatalkan.');

            return self::SUCCESS;
        }

        DB::transaction(function (): void {
            $this->info('Hapus pembayaran & tiket pembayaran...');
            Payment::query()->delete();
            PaymentTicket::query()->delete();

            $this->info('Hapus invoice & riwayat perubahan paket...');
            Invoice::query()->delete();
            PackageChangeRequest::query()->delete();

            $this->info('Hapus tiket gangguan & pesan tiket...');
            DB::table('ticket_messages')->delete();
            Ticket::query()->delete();

            $this->info('Hapus notifikasi & log isolir...');
            WhatsAppNotification::query()->delete();
            IsolirLog::query()->delete();

            $this->info('Hapus data pelanggan...');
            Customer::query()->delete();
        });

        $this->info('Berhasil! Data pelanggan dan relasinya sudah bersih.');

        return self::SUCCESS;
    }
}
