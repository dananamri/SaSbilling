<?php

namespace App\Console\Commands;

use App\Support\TrafficDemo;
use Illuminate\Console\Command;

class TrafficDemoCleanupCommand extends Command
{
    protected $signature = 'traffic:demo:cleanup {--force : Hapus meski masa berlaku belum habis}';

    protected $description = 'Menghapus data demo trafik chart (trafik bulanan) yang masa berlakunya telah habis.';

    public function handle(): int
    {
        if (TrafficDemo::cleanup(force: (bool) $this->option('force'))) {
            $this->info('Data demo trafik berhasil dihapus.');
        } else {
            $this->info('Tidak ada data demo trafik yang perlu dihapus.');
        }

        return self::SUCCESS;
    }
}
