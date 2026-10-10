<?php

namespace App\Enums;

enum CustomerStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Isolated = 'isolated';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Persetujuan',
            self::Active => 'Aktif',
            self::Isolated => 'Terisolir',
            self::Inactive => 'Nonaktif',
        };
    }
}
