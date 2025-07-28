<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;


class Handler extends ExceptionHandler
{
    protected $levels = [
        // 'App\Exceptions\MyException' => 'critical',
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Custom error reporting (e.g., Sentry, Bugsnag)
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return redirect('/')->with('error', 'Session expired, please try again.');
        }

        return parent::render($request, $exception);
    }
}
