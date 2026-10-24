<?php

namespace App\Support;

use App\Models\RolePermission;
use App\Models\User;

class AdminPermissions
{
    /**
     * Daftar menu sidebar yang dapat diatur hak aksesnya, dikelompokkan per bagian sidebar.
     * Key = identifier yang disimpan di tabel role_permissions, label = teks menu di sidebar.
     *
     * @var array<string, array<string, string>>
     */
    public const SECTIONS = [
        'Manajemen' => [
            'customers' => 'Pelanggan',
            'users' => 'Pengguna',
            'packages' => 'Paket Layanan',
            'package-change-requests' => 'Permintaan Upgrade',
            'book-entries' => 'Pembukuan',
            'cash-deposits' => 'Setoran Kasir',
            'invoices' => 'Invoice',
            'payments' => 'Pembayaran',
            'isolir' => 'Isolir',
        ],
        'Network' => [
            'routers' => 'Monitoring MikroTik',
            'bandwidth' => 'Bandwidth Monitoring',
            'pppoe' => 'PPPoE Management',
            'pppoe-profiles' => 'Profile PPPoE',
            'radius' => 'RADIUS Mobile',
            'olts' => 'OLT Network',
            'gacs' => 'GACS V2',
            'map' => 'Peta Pelanggan',
            'tenants' => 'Kelola Tenant',
        ],
        'Hotspot' => [
            'hotspot-profiles' => 'Profil',
            'hotspot-vouchers' => 'Voucher',
            'hotspot-members' => 'Member',
            'hotspot-rekap' => 'Rekap',
        ],
        'Operasional' => [
            'payment-tickets' => 'Tiket Pembayaran',
            'tickets' => 'Laporan Gangguan',
            'attendances' => 'Absensi',
            'activity-logs' => 'Aktivitas Terbaru',
        ],
        'Sistem' => [
            'notifications' => 'Notifikasi',
            'backup' => 'Backup',
            'reports' => 'Laporan',
            'settings' => 'Pengaturan',
        ],
    ];

    /**
     * Pencocokan nama route admin.* ke key menu.
     * Urutan penting: route paling spesifik harus berada sebelum route generik
     * (contoh: admin.pppoe.profiles sebelum admin.pppoe).
     *
     * @var array<string, string>
     */
    public const ROUTE_MAP = [
        'admin.pppoe.profiles' => 'pppoe-profiles',
        'admin.pppoe' => 'pppoe',
        'admin.hotspot.profiles' => 'hotspot-profiles',
        'admin.hotspot.vouchers' => 'hotspot-vouchers',
        'admin.hotspot.members' => 'hotspot-members',
        'admin.hotspot.rekap' => 'hotspot-rekap',
        'admin.package-change-requests' => 'package-change-requests',
        'admin.book-entries' => 'book-entries',
        'admin.cash-deposits' => 'cash-deposits',
        'admin.payment-tickets' => 'payment-tickets',
        'admin.activity-logs' => 'activity-logs',
        'admin.attendances' => 'attendances',
        'admin.customers' => 'customers',
        'admin.users' => 'users',
        'admin.packages' => 'packages',
        'admin.invoices' => 'invoices',
        'admin.payments' => 'payments',
        'admin.isolir' => 'isolir',
        'admin.routers' => 'routers',
        'admin.bandwidth' => 'bandwidth',
        'admin.radius' => 'radius',
        'admin.olts' => 'olts',
        'admin.gacs' => 'gacs',
        'admin.map' => 'map',
        'admin.tenants' => 'tenants',
        'admin.tickets' => 'tickets',
        'admin.notifications' => 'notifications',
        'admin.backup' => 'backup',
        'admin.reports' => 'reports',
        'admin.settings' => 'settings',
    ];

    /**
     * Cache izin per role dalam satu request proses (di-reset saat data berubah).
     *
     * @var array<string, array<string, string>|null>
     */
    private static array $cache = [];

    /**
     * Cocokkan nama route admin.* dengan key menu pada ROUTE_MAP.
     */
    public static function resolveKey(string $routeName): ?string
    {
        foreach (self::ROUTE_MAP as $prefix => $key) {
            if ($routeName === $prefix || str_starts_with($routeName, $prefix.'.')) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Apakah user ini boleh mengakses menu tertentu?
     *
     * - Super Admin selalu diizinkan.
     * - Role yang belum pernah disetel dianggap diizinkan semua (default aman).
     * - Role yang sudah disetel hanya diizinkan untuk key yang tercentang.
     */
    public static function can(?User $user, string $key): bool
    {
        if ($user === null) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        $role = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;
        $allowed = self::allowedFor($role !== null ? (string) $role : null);

        if ($allowed === null) {
            return true;
        }

        return in_array($key, $allowed, true);
    }

    /**
     * Daftar key menu yang diizinkan untuk suatu role.
     * null = role tersebut belum pernah disetel (berlaku semua diizinkan).
     *
     * @return array<string, string>|null
     */
    public static function allowedFor(?string $role): ?array
    {
        if ($role === null) {
            return null;
        }

        if (! array_key_exists($role, self::$cache)) {
            self::$cache[$role] = RolePermission::where('role', $role)->first()?->permissions;
        }

        return self::$cache[$role];
    }

    /**
     * Semua key menu yang tersedia (untuk validasi input form hak akses).
     *
     * @return array<int, string>
     */
    public static function allKeys(): array
    {
        return array_keys(array_merge(...array_values(self::SECTIONS)));
    }

    /**
     * Apakah sebuah bagian sidebar masih punya menu yang terlihat untuk user ini?
     */
    public static function sectionVisible(string $section): bool
    {
        $menus = self::SECTIONS[$section] ?? [];

        foreach (array_keys($menus) as $key) {
            if (self::can(auth()->user(), $key)) {
                return true;
            }
        }

        return false;
    }

    public static function flushCache(): void
    {
        self::$cache = [];
    }
}
