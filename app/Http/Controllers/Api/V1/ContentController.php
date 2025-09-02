<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Services\ContentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    use ApiResponse;

    public function __construct(private ContentService $content)
    {
        $this->middleware('credits')->only('index');
    }

    public function index(Request $request)
    {
        $content = $this->content->index();
        return $this->success(ContentResource::collection($content));
    }
}