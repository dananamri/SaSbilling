<?php

namespace App\Enums;

enum CustomerStatus: string
{
    case Active = 'active';
    case Isolated = 'isolated';
    case Inactive = 'inactive';
}
