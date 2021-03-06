<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */

    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        //Valida errores de permisos
        if ($this->isHttpException($e))
        {
//            return response()->view('errors/503', [], 404);
            switch ($e->getStatusCode()) 
                {
                // not found
                case 404:
                return redirect()->guest('error/404');
                break;

                // internal error
                case '500':
                return redirect()->guest('error/500');
                break;
                default:
                    return redirect('/');
                break;
            }
        }

        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->back()->withInput()->with('token', csrf_token());
        }


        return parent::render($request, $e);
    }
}
