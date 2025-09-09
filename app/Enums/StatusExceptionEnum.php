<?php

namespace App\Enums;

enum StatusExceptionEnum :string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PENDING = 'pending';

    public function label(): string
    {
        return __('site.' . $this->value);
    }
}
