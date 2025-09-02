<?php

namespace App\Services\Contracts;

use App\Models\User;

interface CreditPolicyInterface
{
    /** Проверяет и возвращает: можно ли списывать кредит, и сколько списать. */
    public function allowAndCost(User $user): int;
}
