<?php

namespace App\Console\Commands;

use App\Support\TrafficDemo;
use Illuminate\Console\Command;

class TrafficDemoCommand extends Command
{
    protected $signature = 'traffic:demo {--minutes=2 : Lama data demo bertahan, dalam menit}';

    protected $description = 'Menyisipkan data demo trafik 6 bulan untuk pratinjau UI chart (dihapus otomatis setelah masa berlaku habis).';

    public function handle(): int
    {
        $minutes = max(1, (int) $this->option('minutes'));
        $expiresAt = TrafficDemo::seed($minutes);

        $this->info('Data demo trafik 6 bulan berhasil dibuat (18 snapshot + 1 router demo).');
        $this->info('Masa berlaku: '.$minutes.' menit — otomatis terhapus pada '.$expiresAt->format('d M Y H:i:s').'.');
        $this->comment('Pembersihan berjalan otomatis saat jadwal scheduler aktif, atau saat Dashboard dibuka kembali setelah masa berlaku habis.');

        return self::SUCCESS;
    }
}
