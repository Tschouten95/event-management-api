<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;

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
     * 
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Return a custom json response for exceptions
     * 
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    public function render($request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $response = parent::render($request, $exception);

        if (!$response instanceof JsonResponse) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => $response->status(),
            ], $response->status());
        }
    
        return $response;
    }
}
