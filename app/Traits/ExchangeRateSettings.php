<?php

namespace App\Traits;

use App\Models\Views\ExchangeRateWithCurrencyCodeView;
use App\Statics\ResponseCode;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

trait ExchangeRateSettings
{
	use ApiResponser;

	/**
	 * Find Exchange Rate setting with Exchange Rate ID
	 *
	 * @param  uuid                      $id
	 * @param  \Illuminate\Http\Request  $request
	 * @return mixed
	 */
	public function getSetting(string $id, Request $request)
	{
		$exrSetting = $this->get($id, $request->withDeleted);

		if ($exrSetting) {
			return $this->successResponse($exrSetting,
				ResponseCode::GET_EXCHANGE_RATE_SETTING_BY_EXRATE_ID_SUCCESS);
		}
		return $this->successResponse("Exchange Rate Setting not found!",
			ResponseCode::GET_EXCHANGE_RATE_SETTING_BY_EXRATE_ID_FAILURE);
	}

	/**
	 * Update Exchange Rate
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return mixed
	 */
	public function updateSetting(Request $request)
	{
		$validateResult = $this->validator($request->all());
		// is Validation Failed
		if ($validateResult->fails()) {
			Log::info("Update Rate Setting - Validation Failed");
			Log::info($validateResult->errors());
			return $this->successResponse($validateResult,
				ResponseCode::UPDATE_EXCHANGE_RATE_SETTING_VALIDATION_FAILED);
		}

		$errMsg = "Unknown Error while Updating Rate Setting";
		try {
			$updateResult = $this->save($request);
			if ($updateResult instanceof \App\Models\ExchangeRateSetting)
			{
				return $this->successResponse($updateResult,
					ResponseCode::UPDATE_EXCHANGE_RATE_SETTING_SUCCESS);
			}
			else
			{
				return $this->successResponse($updateResult,
					ResponseCode::UPDATE_EXCHANGE_RATE_SETTING_FAILURE);
			}
		} catch (ModelNotFoundException $e) {
			Log::error('ModelNotFoundException'. $e);
			$errMsg = 'Exchange Rate Setting Model not found! Please contact developer';
		} catch (\Exception $e) {
			Log::error('Exception'. $e);
			$errMsg = $e->getMessage();
		}

		return $this->successResponse($errMsg, ResponseCode::UPDATE_EXCHANGE_RATE_SETTING_ERROR);
	}
}