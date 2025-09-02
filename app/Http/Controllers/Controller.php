<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @method \Illuminate\Routing\ControllerMiddlewareOptions middleware(string|string[] $middleware)
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}