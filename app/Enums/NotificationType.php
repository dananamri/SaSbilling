<?php

namespace App\Enums;

enum NotificationType: string
{
    case Reminder = 'reminder';
    case Info = 'info';
    case PaymentReceived = 'payment_received';
    case Isolated = 'isolated';
    case Reopened = 'reopened';
}
