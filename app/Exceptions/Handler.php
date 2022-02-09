<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {

        if ($request->expectsJson() && $e instanceof ModelNotFoundException) {

            return Route::respondWithRoute('api.fallback');
        }

        if ($request->expectsJson() && $e instanceof AuthorizationException) {
            return response()->json(['message' => $e->getMessage()], 403);
        }

        if ($request->expectsJson() && $e instanceof TokenInvalidException) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return parent::render($request, $e);
    }
}
