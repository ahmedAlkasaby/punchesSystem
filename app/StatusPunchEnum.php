<?php

namespace App;

enum StatusPunchEnum : string
{
    case APPROVED = 'approved';
    case LATE = 'late';
    case EARLY_LEAVE = 'early_leave';
    case OUT_REDIS = 'out_redis';

    public function label(): string
    {
        return __('site.' . $this->value);
    }


}
