<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            abort(404);
        }

        if($this->isHttpException($exception)) {
            // 403
            if($exception->getStatusCode() == 403) {
                return response()->view('errors.403', [], 403);
            }
            // 4**は404に返す
            if (preg_match('/^4\d{2}$/', $exception->getStatusCode())) {
                return response()->view('errors.404', [], 404);
            }
            // 500
            return response()->view('errors.500', array('code' => $exception->getStatusCode()), $exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }
}
