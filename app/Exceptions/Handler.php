<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Mockery\VerificationExpectation;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
        if($e instanceof MissingAbilityException)
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        if($e instanceof NotFoundHttpException)
        {
            return response()->json(['Message'=>'You are trying  access data not found'],404);
        }

        if($e instanceof HttpException && $e->getMessage() === 'Your email address is not verified.')
        {
            return response()->json(['Message'=>'Your Email Must be Verified first'],403);
        }

        if($e instanceof HttpException)
        {
            return response()->json(['Message'=>'something went wrong please Contact with admin'],500);
        }

        return parent::render($request,$e);
    }

}
