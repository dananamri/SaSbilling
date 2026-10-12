<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'superadmin';
    case Admin = 'admin';
    case Kasir = 'kasir';
    case Teknisi = 'teknisi';
    case Helpdesk = 'helpdesk';
    case Noc = 'noc';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Kasir => 'Kasir / Kolektor',
            self::Teknisi => 'Teknisi Lapangan',
            self::Helpdesk => 'Helpdesk / CS',
            self::Noc => 'NOC Network',
        };
    }
}
