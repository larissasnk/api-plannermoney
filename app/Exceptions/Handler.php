<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;


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
    
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Erro de validação',
                'message' => $exception->getMessage(),
                'errors' => $exception->errors() // retorna os erros de validação específicos
            ], 422);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'error' => 'Algo deu errado!',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ], 500);
        }

        return parent::render($request, $exception);
    }


    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    public function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => $exception->getMessage()
        ], 401);
    }
}
