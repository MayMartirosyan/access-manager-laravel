<?php

namespace App\Services\Policies;

use App\Models\User;
use App\Services\Contracts\CreditPolicyInterface;
use Illuminate\Support\Carbon;

class FixedDailyPolicy implements CreditPolicyInterface
{
    public function allowAndCost(User $user): int
    {

        $now = now();
        if (!$user->last_credits_reset_at || !Carbon::parse($user->last_credits_reset_at)->isSameDay($now)) {
            $user->credits_remaining = $user->roles()->max('daily_credits') ?: 100;
            $user->last_credits_reset_at = $now;
            $user->save();
        }

        if (($user->credits_remaining ?? 0) < 1) {
            return -1;
        }
        return 1;
    }
}
