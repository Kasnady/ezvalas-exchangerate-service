<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Validation\Validator;
use Log;

trait ApiResponser
{
	/**
	 * Building success response
	 * @param $data
	 * @param int $code
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function successResponse($data, $statusCode=Response::HTTP_OK, $code = Response::HTTP_OK)
	{
		return $this->response($code, $statusCode, $data);
	}

	/**
	 * Building error response
	 * @param $data
	 * @param int $code
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function errorResponse($message, $code)
	{
		return \response()->json(['error' => $message, 'code' => $code], $code);
	}


	/**
	 * Building success response
	 * @param int    $httpCode
	 * @param String $stsCode
	 * @param        $data
	 * @return \Illuminate\Http\JsonResponse
	 */
	private static function response($httpCode, $stsCode, $data) {
		if ($data instanceof \Illuminate\Validation\Validator && $data->fails())
		{
			Log::info("Failed Validation - converting errors to string");
			$data = self::convertValidationMsgToString($data);
		}

		return \response()->json([
				'status'		=> $stsCode,
				'data'			=> $data,
			], $httpCode);
	}

	/**
	 * Convert Failed Validation Messages to String
	 * @param Illuminate\Validation\Validator
	 * @return String
	 */
	private static function convertValidationMsgToString(Validator $validator) {
		$errMsg = null;
		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $message) {
				$errMsg .= (empty($errMsg) ? '' : "\n").$message;
			}
		}
		return $errMsg;
	}
}