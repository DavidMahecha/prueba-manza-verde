<?php

namespace App\Exceptions;

use App\Traits\ResponseApi;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseApi;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse(["Registro [{$model}] no encontrado"], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ValidationException) {
            return $this->errorResponse([$exception->errors()], Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse(['Ruta no encontrada'], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedException) {
            return $this->errorResponse(['Método no valido'], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse(['Usuario no autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->errorResponse(['Error interno. '.$exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
