<?php

namespace App\Models;

class LogHelper
{
	/**
	 * Add log from Ori table data to Log Table data
	 *
	 * @param  mixed $ori
	 * @param  mixed $logClass
	 * @return
	 */
	public static function addLogFrom($ori, $logClass)
	{
		$logClass::create($ori->toArray());
	}
}
