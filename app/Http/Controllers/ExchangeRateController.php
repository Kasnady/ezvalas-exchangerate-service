<?php

namespace App\Http\Controllers;

use App\Exceptions\InputDataInsufficientException;
use App\Models\ExchangeRate as ExchangeRateModel;
use App\Models\Views\ExchangeRateWithCurrencyCodeView;
use App\Traits\ExchangeRates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class ExchangeRateController extends Controller
{
	use ExchangeRates;

	private static $_instance;

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
	 * Initiate Class
	 *
	 * @return self
	 */
	public static function getInstance()
	{
		if (!(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Get Exchange Rate data
	 *
	 * @param  string  $sortBy
	 * @param  string  $sortType
	 * @param  boolean $withDeleted
	 * @param  string  $withDeleted
	 * @return mixed
	 */
	protected function get($sortBy, $sortType, $withDeleted=false, $id=null)
	{
		$sortBy = $sortBy ?? 'from_country_id';
		$sortType = $sortType ?? 'ASC';

		$exchangeRate = ExchangeRateWithCurrencyCodeView::orderBy($sortBy, $sortType);

		if ($withDeleted) {
			$exchangeRate = $exchangeRate->withTrashed();
		}

		if (!empty($id)) {
			return $exchangeRate->find($id);
		}
		return $exchangeRate->get();
	}

	/**
	 * Find Exchange Rate using ID
	 *
	 * @param  string  $id
	 * @param  boolean $withDeleted
	 * @return mixed
	 */
	public function find($id, $withDeleted=false)
	{
		return $this->get(null, null, $withDeleted, $id);
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
			Log::info("Updating Rate - find Rate data for ID# $id first");
			$exRate = ExchangeRateModel::withTrashed()->findOrFail($id);
		} else {
			Log::info("Adding Rate");

			$exRate = new ExchangeRateModel();
			$exRate->created_by = $data->updatedBy;
			$exRate->delete(); // should be inactive at first
		}
		$exRate->from_country_id = $data->fromCountryId;
		$exRate->to_country_id = $data->toCountryId;
		$exRate->buy_rate = $data->buyRate;
		$exRate->base_rate = $data->baseRate;
		$exRate->sell_rate = $data->sellRate;
		$exRate->updated_by = $data->updatedBy;
		$exRate->saveOrFail();
		Log::info("Adding/Updating Rate Succeed");

		if ($data->isDelete)
		{
			$exRate->delete();
		} else {
			if ($this->find($id, true)->allow_update_public)
			{
				$exRate->restore();
			}
			else
			{
				return "Unable to publish exchange rate due to minimal sell rate haven't been set";
			}
		}

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
			'isDelete'=>'nullable|boolean',
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
