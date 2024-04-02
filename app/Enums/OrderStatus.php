<?php

namespace App\Enums;

enum OrderStatus: string
{
    case COMPLETED = 'completed';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';
}
