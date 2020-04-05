<?php

namespace App\Http\Controllers;

use App\Exceptions\InputDataInsufficientException;
use App\Models\ExchangeRate as ExchangeRateModel;
use App\Traits\ExchangeRates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class ExchangeRateController extends Controller
{
	use ExchangeRates;

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
	 * Save Exchange Rate
	 *
	 * @param  object $data
	 * @param  int    $id
	 * @return \App\Models\ExchangeRate
	 */
	protected function save($data, $id=null)
	{
		if ($id) {
			// Got ID
			Log::info("Updating Rate");
			$exRate = ExchangeRateModel::withTrashed()->findOrFail($id);
		} else {
			Log::info("Adding Rate");
			$exRate = new ExchangeRateModel();
			$exRate->created_by = $data->updatedBy;
		}
		$exRate->from_country_id = $data->fromCountryId;
		$exRate->to_country_id = $data->toCountryId;
		$exRate->buy_rate = $data->buyRate;
		$exRate->base_rate = $data->baseRate;
		$exRate->sell_rate = $data->sellRate;
		$exRate->updated_by = $data->updatedBy;
		$exRate->saveOrFail();
		Log::info("Adding/Updating Rate Succeed");

		return $exRate;
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
			'id'=> 'nullable|uuid',
			'fromCountryId' => 'required|numeric|different:toCountryId',
			'toCountryId'=>'required|numeric|different:fromCountryId',
			'buyRate'=>'required|numeric|min:0|lt:baseRate',
			'baseRate'=>'required|numeric|min:0',
			'sellRate'=>'required|numeric|min:0|gt:baseRate',
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
