<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        if ($request->is('api/*')) {
            $error_code = $code = ApiException::INTERNAL_ERROR;
            $reason = $e->getMessage();
            $message = ApiException::getErrorMessage($error_code);
            if ($e instanceof ApiException) {
                $code = $e->getCode();
                $error_code = $e->getErrorCode();
                $reason = $e->getReason();
                $message = ApiException::getErrorMessage($error_code);
            }
            if ($e instanceof ModelNotFoundException || $e instanceof RelationNotFoundException) {
                $code = $error_code = ApiException::NOT_FOUND;
                $reason = 'Resource not found';
                $message = ApiException::getErrorMessage($code);
            }
            if ($e instanceof NotFoundHttpException) {
                $error_code = $code = $e->getStatusCode();
                $reason = 'Endpoint not found';
                $message = ApiException::getErrorMessage($code);
            }
            if ($e instanceof MethodNotAllowedHttpException) {
                $error_code = $code = $e->getStatusCode();
                $reason = 'Method not allowed';
                $message = ApiException::getErrorMessage($code);
            }
            $context = [
                'success' => false,
                'error' => [
                    'code' => $error_code,
                    'message' => $message,
                    'reason' => $reason,
                ],
            ];

            return response()->json($context, $code);
        }

        return parent::render($request, $e);
    }
}
