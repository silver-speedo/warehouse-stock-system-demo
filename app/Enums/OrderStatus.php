<?php

namespace App\Enums;

use App\Traits\BackedEnumHelper;

enum OrderStatus: string
{
    use BackedEnumHelper;

    case PLACED = 'placed';
    case DISPATCHED = 'dispatched';
    case CANCELLED = 'cancelled';
}
