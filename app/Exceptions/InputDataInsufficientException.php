<?php

namespace App\Exceptions;

use Exception;
use Log;

class InputDataInsufficientException extends Exception
{

	private $msg="Input data insufficient exception!";

	/**
	 * Report the exception.
	 *
	 * @return void
	 */
	public function report()
	{
		Log::debug('Input data insufficient');
	}

	/**
	 * Create a new validation exception from a plain message.
	 *
	 * @param  string  $message
	 * @return static
	 */
	public static function withMessage($message="")
	{
		self::$msg = $message;
	}

	public static function message()
	{
		return self::$msg;
	}
}
