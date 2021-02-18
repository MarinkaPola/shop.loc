<?php

namespace App\Exceptions;

use App;
use Exception;
use Symfony\Component\{HttpFoundation\Response,
    HttpKernel\Exception\MethodNotAllowedHttpException,
    HttpKernel\Exception\NotFoundHttpException,
    HttpKernel\Exception\UnauthorizedHttpException
};
use Throwable;
use Illuminate\{Auth\AuthenticationException,
    Foundation\Exceptions\Handler as ExceptionHandler,
    Http\JsonResponse,
    Http\Request,
    Validation\ValidationException,
    Auth\Access\AuthorizationException,
    Database\Eloquent\ModelNotFoundException};


class Handler extends ExceptionHandler
{
    public const MESSAGE_VAR = 'data';

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
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response | JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            $response = [
                'success' => false,
                self::MESSAGE_VAR => null
            ];

            if ($exception instanceof ValidationException) {
                $message = $exception->errors();
                $statusCode = 422;
            } elseif ($exception instanceof UnauthorizedHttpException or $exception instanceof AuthenticationException) {
                $message = __('system.401');
                $statusCode = 401;
            } elseif ($exception instanceof AuthorizationException) {
                $message = __('system.403');
                $statusCode = 403;
            } elseif ($exception instanceof ModelNotFoundException or $exception instanceof NotFoundHttpException) {
                $message = __('system.404');
                $statusCode = 404;
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                $message = __('system.405');
                $statusCode = 405;
            } else {
                $statusCode = method_exists($exception, 'getStatusCode')
                    ? $exception->getStatusCode() : 500;
                $message = $exception->getMessage();

                // more info in debug mode
                if (config('app.debug')) {
                    $debug['type'] = class_basename($exception);
                    $debug['file'] = $exception->getFile();
                    $debug['line'] = $exception->getLine();
                    $debug['trace'] = $exception->getTrace()
                        ?: explode(PHP_EOL, $exception->getTraceAsString());
                    $response['debug'] = $debug;
                }
            }
            $response[self::MESSAGE_VAR] = $message;

            return response()->json($response, $statusCode);
        }
        return parent::render($request, $exception);
    }
}
