<?php
//
//namespace App\Exceptions;
//
//use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
//use Illuminate\Session\TokenMismatchException;
//use Throwable;
//
//class CustomExceptionHandler extends ExceptionHandler
//{
//    protected $dontReport = [
////        TokenMismatchException::class,
//    ];
//
//    public function render($request, Throwable $exception)
//    {
//        if ($exception instanceof TokenMismatchException) {
//            return redirect()->route('login')->with('message', 'Your session has expired. Please log in again.');
//        }
//
//        return parent::render($request, $exception);
//    }
//}
