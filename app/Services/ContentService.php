<?php

namespace App\Services;

use App\Models\Content;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ContentService extends BaseService
{
    /**
     * Get all content.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return Cache::remember('content.index', now()->addMinutes(5), function () {
            return Content::query()
                ->orderBy('display_date', 'desc')
                ->get();
        });
    }
}