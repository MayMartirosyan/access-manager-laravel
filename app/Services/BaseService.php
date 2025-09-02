<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected function tx(callable $callback)
    {
        return DB::transaction(fn() => $callback());
    }
}
