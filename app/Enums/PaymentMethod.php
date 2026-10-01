<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case TransferBCA = 'transfer_bca';
    case TransferMandiri = 'transfer_mandiri';
    case TransferBNI = 'transfer_bni';
    case TransferBRI = 'transfer_bri';
    case Qris = 'qris';
    case Dana = 'dana';
    case GoPay = 'gopay';
    case Ovo = 'ovo';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Tunai',
            self::TransferBCA => 'Transfer BCA',
            self::TransferMandiri => 'Transfer Mandiri',
            self::TransferBNI => 'Transfer BNI',
            self::TransferBRI => 'Transfer BRI',
            self::Qris => 'QRIS',
            self::Dana => 'DANA',
            self::GoPay => 'GoPay',
            self::Ovo => 'OVO',
        };
    }
}
