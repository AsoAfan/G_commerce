<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *
 *         title="My Laravel API",
 *         description="This is a sample Laravel API documentation.",
 *         termsOfService="https://www.example.com/terms",
 *         @OA\Contact(
 *             name="John Doe",
 *             email="john.doe@example.com",
 *             url="https://www.example.com/john-doe"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *         ),
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Local development server"
 *     ),
 *     @OA\PathItem (
 *     path="api/"
 *      ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
