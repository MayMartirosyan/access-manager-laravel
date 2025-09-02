<?php

use App\Models\Content;
use App\Services\ContentService;
use Illuminate\Support\Facades\Cache;

it('returns all content ordered by display_date', function () {
    Cache::flush();
    Content::truncate();
    Content::factory()->create(['display_date' => '2025-09-01']);
    Content::factory()->create(['display_date' => '2025-09-02']);
    Content::factory()->create(['display_date' => '2025-08-31']);

    $service = app(ContentService::class);
    $content = $service->index();

    expect($content)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    expect($content)->toHaveCount(3);
    expect($content->first()->display_date->toDateString())->toBe('2025-09-02');
    expect($content->last()->display_date->toDateString())->toBe('2025-08-31');
});

it('caches content index', function () {
    Cache::flush();
    Content::truncate();
    Content::factory()->count(2)->create();

    $service = app(ContentService::class);
    $content = $service->index();

    expect(Cache::has('content.index'))->toBeTrue();
    expect($content)->toHaveCount(2);
});