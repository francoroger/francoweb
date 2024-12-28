<?php

namespace App\Exceptions;

<<<<<<< HEAD
use Throwable;
=======
use Exception;
>>>>>>> 7e5b37cb9f83395d9e052fa4aaa0900d02db0493
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
<<<<<<< HEAD
     * @var array<int, class-string<Throwable>>
=======
     * @var array
>>>>>>> 7e5b37cb9f83395d9e052fa4aaa0900d02db0493
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
<<<<<<< HEAD
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
=======
     * @var array
     */
    protected $dontFlash = [
>>>>>>> 7e5b37cb9f83395d9e052fa4aaa0900d02db0493
        'password',
        'password_confirmation',
    ];

    /**
<<<<<<< HEAD
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
=======
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
        return parent::render($request, $exception);
>>>>>>> 7e5b37cb9f83395d9e052fa4aaa0900d02db0493
    }
}
