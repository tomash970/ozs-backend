<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{

    use ApiResponser;
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
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
    public function render($request, Exception $exception)
    {
                //User is trying to validate inputs
        if ($exception instanceof ValidationException) {
           return $this->convertValidationExceptionToResponse($exception, $request);
        }

        //User is trying to use model that does not egsist(for example non existing user data)
        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));
           return $this->errorResponse("Model {$modelName} with specified indetificator does not exist!", 404);
        }

        //User is not authennticated
        if ($exception instanceof AuthenticationException) {
           return $this->unauthenticated($request, $exception);
        }

        //User do not have enough permisions to what he wants to do
        if ($exception instanceof AuthorizationException) {
           return $this->errorResponse($exception->getMessage(), 403);
        }

        //User calls wrong method (POST; GET)
        if ($exception instanceof MethodNotAllowedException) {
           return $this->errorResponse('The specified method could not be found', 405);
        }


        //User calls wrong adress
        if ($exception instanceof NotFoundHttpException) {
           return $this->errorResponse('The specified URL cannot be found', 404);
        }

        //For all others
        if ($exception instanceof HttpException) {
           return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        //Database foreign key constranis error
        if ($exception instanceof QueryException) {
            //dd($exception);
           $errorCode = $exception->errorInfo[1];

           if ($errorCode == 1451) {
               return $this->errorResponse('Cannot remove this resource premanently because of database constrains!', 409);
           }
        }

        //izvan produkcije
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        //za sve ostalo tu je master-card ali samo u produkciji
        return $this-errorResponse('Unespected Exception. Try later', 500);
    }


    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated', 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
       
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
    }
}
