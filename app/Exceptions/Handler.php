<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {

        $this->renderable(function (Throwable $e) {
            //
        });

        $this->reportable(function (Throwable $e) {
            //
        });

    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // TODO: redirect to front-end login page
        return $this->shouldReturnJson($request, $exception)
            ? response()->json(['message' => $exception->getMessage(), 'code' => 401], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

}
