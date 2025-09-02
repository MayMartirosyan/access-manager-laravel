<?php

namespace App\Services\Policies;

use App\Models\User;
use App\Services\Contracts\CreditPolicyInterface;

class UnlimitedPolicy implements CreditPolicyInterface
{
    public function allowAndCost(User $user): int
    {
        return 0; // Админ — бесплатно
    }
}
