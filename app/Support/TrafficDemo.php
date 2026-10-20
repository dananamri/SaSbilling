<?php

namespace App\Support;

use App\Models\BandwidthSnapshot;
use App\Models\Router;
use App\Models\Setting;
use Illuminate\Support\Carbon;

class TrafficDemo
{
    public const SETTING_KEY = 'traffic_demo';

    /**
     * Menyisipkan data demo trafik 6 bulan terakhir untuk pratinjau UI chart.
     *
     * Data demo dicatat di settings{traffic_demo} beserta masa berlakunya,
     * sehingga hanya baris demo inilah yang akan dihapus otomatis —
     * data trafik asli dari router tidak tersentuh.
     */
    public static function seed(int $minutes = 2): Carbon
    {
        self::cleanup(force: true);

        $router = Router::create([
            'name' => 'DEMO Traffic (autohapus)',
            'host' => '192.0.2.10',
            'username' => 'demo',
            'password_encrypted' => 'demo-tanpa-akses',
            'description' => 'Router simulasi untuk demo chart trafik. Dihapus otomatis bersama datanya.',
            'is_active' => false,
        ]);

        $ids = [];
        foreach (range(5, 0) as $offset) {
            $month = now()->subMonths($offset);

            foreach (range(1, 3) as $porsi) {
                $recordedAt = $month->copy()->day(min(4 + ($porsi - 1) * 10, 26))->setTime(8 + $porsi, 15);

                $snapshot = BandwidthSnapshot::create([
                    'router_id' => $router->id,
                    'interface_name' => 'demo-'.$porsi,
                    'rx_bytes' => random_int(45, 210) * 1024 ** 3,
                    'tx_bytes' => random_int(14, 72) * 1024 ** 3,
                    'rx_bps' => random_int(200_000, 2_500_000),
                    'tx_bps' => random_int(80_000, 900_000),
                    'recorded_at' => $recordedAt,
                ]);

                // Chart dashboard dikelompokkan berdasarkan created_at,
                // jadi waktu pembuatan baris ikut diatur ke bulan yang sama.
                $snapshot->timestamps = false;
                $snapshot->created_at = $recordedAt;
                $snapshot->saveQuietly();

                $ids[] = $snapshot->id;
            }
        }

        $expiresAt = now()->addMinutes($minutes);
        Setting::set(self::SETTING_KEY, json_encode([
            'router_id' => $router->id,
            'snapshot_ids' => $ids,
            'expires_at' => $expiresAt->toIso8601String(),
        ]));

        return $expiresAt;
    }

    /**
     * Hapus data demo trafik. Tanpa $force, hanya dihapus jika masa berlaku sudah lewat.
     */
    public static function cleanup(bool $force = false): bool
    {
        $raw = Setting::get(self::SETTING_KEY);

        if ($raw === null) {
            return false;
        }

        $demo = is_string($raw) ? json_decode($raw, true) : $raw;

        if (! is_array($demo)) {
            Setting::where('key', self::SETTING_KEY)->delete();

            return false;
        }

        $expiresAt = isset($demo['expires_at']) ? Carbon::parse($demo['expires_at']) : null;

        if (! $force && ($expiresAt === null || now()->lessThan($expiresAt))) {
            return false;
        }

        BandwidthSnapshot::whereIn('id', $demo['snapshot_ids'] ?? [])->delete();
        Router::whereIn('id', array_filter([$demo['router_id'] ?? null]))->delete();
        Setting::where('key', self::SETTING_KEY)->delete();

        return true;
    }
}
