<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ExchangeRateController;
use App\Exceptions\InputDataInsufficientException;
use App\Models\ExchangeRateSetting as ExchangeRateSettingModel;
use App\Traits\ExchangeRateSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class ExchangeRateSettingController extends Controller
{
	use ExchangeRateSettings;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Get Exchange Rate Setting by Rate ID
	 *
	 * @param  string  $rateId
	 * @param  boolean $withDeleted
	 * @return App\Models\ExchangeRateSetting
	 */
	protected function get(string $rateId, $withDeleted=false)
	{
		$erController = ExchangeRateController::getInstance();

		$exchangeRate = $erController->find($rateId, $withDeleted);
		return $exchangeRate->setting;
	}

	/**
	 * Save Exchange Rate
	 *
	 * @param  object $data
	 * @return \App\Models\ExchangeRateSetting
	 */
	protected function save($data)
	{
		Log::info("Saving Rate Setting");
		$exrSetting = ExchangeRateSettingModel::findOrFail($data->id);

		if ($data->multiplyAmount) {
			$exrSetting->multiply_amount = $data->multiplyAmount;
		}
		if ($data->minSellAmount) {
			$exrSetting->min_sell_amount = $data->minSellAmount;
		}
		if ($data->maxSellAmount) {
			$exrSetting->max_sell_amount = $data->maxSellAmount;
		}

		$exrSetting->updated_by = $data->updatedBy;
		$exrSetting->saveOrFail();
		Log::info("Saving Rate Setting Succeed");
		return $exrSetting;
	}

	/**
	 * Get a validator for an incoming request.
	 *
	 * @param  array  $request
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $request, $strict=true)
	{
		if (empty($request)) {
			throw new InputDataInsufficientException;
		}

		$rules = [
			'id'=> 'uuid|exists:exchange_rate_settings',
			'multiplyAmount'=>'required|numeric|min:1',
			'minSellAmount'=>'required|numeric|min:1|lte:maxSellAmount',
			'maxSellAmount'=>'required|numeric|min:1|gte:minSellAmount',
			'updatedBy'=>'required|uuid'
		];

		// Filter out field not in $request by Key if not strict
		if (!$strict) {
			$rules = array_filter($rules, function($rule) use ($request) {
				$thisKey = key((array) $rule);
				return array_search($thisKey, array_keys($request));
			});
		}

		return Validator::make($request, $rules);
	}
}
