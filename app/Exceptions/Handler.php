<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;
use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // oath exception handling BearerTokenValidator
        "\League\OAuth2\Server\Exception\OAuthServerException",
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

    public function render($request, Throwable  $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof AccessDeniedHttpException || $exception instanceof AuthorizationException) {
                Log::info('start AccessDeniedHttpException, AuthorizationException');
                $data = array(
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                );
                Log::info(json_encode($data));
                Log::info('stop AccessDeniedHttpException, AuthorizationException');
                return response()->json([
                    'success' => false,
                    'error' => config('app.debug') ? $exception->getMessage().' at '.$exception->getLine() : 'Oops! Something went wrong, Try Again after sometime',
                    'message' => 'Oops! Something went wrong, Try Again after sometime'
                ], 500);
            }

            if ($exception instanceof MethodNotAllowedHttpException || $exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                Log::info('start MethodNotAllowedHttpException, ModelNotFoundException, NotFoundHttpException');
                $data = array(
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                );
                Log::info(json_encode($data));
                Log::info('stop MethodNotAllowedHttpException, ModelNotFoundException, NotFoundHttpException');
                return response()->json([
                    'success' => false,
                    'error' => config('app.debug') ? $exception->getMessage().' at '.$exception->getLine() : 'Oops! Something went wrong, Try Again after sometime',
                    'message' => 'Oops! Something went wrong, Try Again after sometime'
                ], 500);
            }
            if ($exception instanceof ThrottleRequestsException) {
                Log::info('start ThrottleRequestsException');
                $data = array(
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                );
                Log::info(json_encode($data));
                Log::info('stop ThrottleRequestsException');
                return response()->json([
                    'success' => false,
                    'error' => config('app.debug') ? $exception->getMessage().' at '.$exception->getLine() : 'Oops! Something went wrong, Try Again after sometime',
                    'message' => 'Oops! Something went wrong, Try Again after sometime'
                ], 500);
            }
        }
        if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException && $exception->getCode() == 9) {
            Log::info('start OAuthServerException');
            $data = array(
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            );
            Log::info(json_encode($data));
            Log::info('stop OAuthServerException');
            return response()->json([
                'success' => false,
                'error' => config('app.debug') ? $exception->getMessage().' at '.$exception->getLine() : 'Oops! Something went wrong, Try Again after sometime',
                'message' => 'Oops! Something went wrong, Try Again after sometime'
            ], 500);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest('/donotezzycaretouch/login');
        }
        return redirect()->guest(route('login'));
    }
}
