<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRateSettingLog extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id', 'exchange_rate_id', 'multiply_amount', 'min_sell_amount',
		'max_sell_amount', 'created_by', 'updated_by', 'created_at',
		'updated_at'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];
}
