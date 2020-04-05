<?php

namespace App\Traits;

use App\Models\ExchangeRate as ExchangeRateModel;
use App\Statics\ResponseCode;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

trait ExchangeRates
{
    use ApiResponser;

	/**
	 * Get All Exchange Rate in DB
	 *
	 * @param  string $sortBy
	 * @param  string $sortType
	 * @return mixed
	 */
    public function list($sortBy='from_country_id', $sortType='ASC')
    {
    	// $exchangeRates = $this->list($sortBy, $sortType);
    	$exchangeRates = ExchangeRateModel::orderBy($sortBy, $sortType)->get();

    	return $this->successResponse($exchangeRates, ResponseCode::GET_EXCHANGE_RATE_LIST_SUCCESS);
    }

	/**
	 * Update Exchange Rate
	 *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
	 */
	public function update(Request $request)
	{
        $validateResult = $this->validator($request->all());
        // is Validation Failed
		if ($validateResult->fails()) {
			Log::info("Update Rate - Validation Failed");
			Log::info($validateResult->errors());
			return $this->successResponse($validateResult, ResponseCode::UPDATE_EXCHANGE_RATE_VALIDATION_FAILED);
		}

		$errMsg = "Unknown Error while Adding/Updating Rate";
		try {
			$updateResult = $this->save($request, $request->id);
			if ($updateResult instanceof \App\Models\ExchangeRate)
			{
				// Update/Add Succeed
				Log::info("END# Succeed Updating Rate");
				return $this->successResponse($updateResult, ResponseCode::UPDATE_EXCHANGE_RATE_SUCCESS);
			}
			else
			{
				return $this->successResponse($updateResult, ResponseCode::UPDATE_EXCHANGE_RATE_FAILURE);
			}
		} catch (ModelNotFoundException $e) {
			Log::error('ModelNotFoundException'. $e);
			$errMsg = 'Exchange Rate Model not found! Please contact developer';
		} catch (\Exception $e) {
			Log::error('Exception'. $e);
			$errMsg = $e->getMessage();
		}

		return $this->successResponse($errMsg, ResponseCode::UPDATE_EXCHANGE_RATE_ERROR);
	}
}