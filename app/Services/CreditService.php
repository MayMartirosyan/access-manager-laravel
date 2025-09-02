<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\CreditPolicyInterface;
use App\Services\Policies\FixedDailyPolicy;
use App\Services\Policies\UnlimitedPolicy;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\DB;

class CreditService extends BaseService
{
    protected function resolvePolicy(User $user): CreditPolicyInterface
    {
        return $user->isAdmin() ? new UnlimitedPolicy() : new FixedDailyPolicy();
    }

    public function consume(User $user): void
    {
        $policy = $this->resolvePolicy($user);
        $cost = $policy->allowAndCost($user);

        if ($cost === -1) {
            throw new HttpClientException('Too Many Requests', 429);
        }

        if ($cost > 0) {
            $this->tx(function () use ($user, $cost) {
                $fresh = User::query()->lockForUpdate()->find($user->id);
                if (($fresh->credits_remaining ?? 0) < $cost) {
                    throw new HttpClientException('Too Many Requests', 429);
                }
                $fresh->credits_remaining -= $cost;
                $fresh->save();
            });
        }
    }
}