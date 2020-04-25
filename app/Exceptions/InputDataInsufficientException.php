<?php

namespace App\Exceptions;

use Exception;
use Log;

class InputDataInsufficientException extends Exception
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(array $request)
	{
		\Log::error('Request Data: '.json_encode($request));

		$this->message = "Input data insufficient or empty";
	}

	/**
	 * Report the exception.
	 *
	 * @return void
	 */
	public function report()
	{
		Log::debug('Input data insufficient');
	}
}
