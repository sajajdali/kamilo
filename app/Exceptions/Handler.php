<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use \Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
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
	 * @param  \Exception $exception
	 *
	 * @return void
	 */
	public function report(Exception $exception) {
		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception $exception
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception) {
		if($exception instanceof AuthorizationException || $exception instanceof UnauthorizedException) {
			return redirect()->route('login');
		}
		if($this->isHttpException($exception)){
		    switch ($exception->getStatusCode()){
                case 404:
                    return response()->view('frontend::errors.404');
                    break;
                case 500:
                    return response()->view('frontend::errors.500');
                    break;
            }
        }
		return parent::render($request, $exception);
	}

	protected function unauthenticated($request, AuthenticationException $exception)
	{
		if($request->expectsJson()){
			return response()->json(['error' => 'Unauthenticated.'], 401);
		}

		return redirect()->guest('login');
	}
}
