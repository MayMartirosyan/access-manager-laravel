<?php

namespace App\Http\Controllers\Web\Content;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function index()
    {
        return Inertia::render('Content/Index', [
            'title' => 'Контент',
        ]);
    }
}