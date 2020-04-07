<?php

namespace App\Models;

use App\traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeRate extends Model
{
	use SoftDeletes, Uuids;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'from_country_id', 'to_country_id', 'buy_rate', 'base_rate',
		'sell_rate', 'created_by', 'deleted_at'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	/**
	 * Is Exchange Rate allowed to publish (restore the deleted_at)
	 *
	 * @return bool
	 */
	public function isAllowPublish()
	{
		return (isset($this->setting()->min_sell_amount) && $this->setting()->min_sell_amount >= 0);
	}

	/**
	 * Relation
	 */

	public function setting()
	{
		return $this->hasOne(ExchangeRateSetting::class, 'id', 'exchange_rate_id');
	}
}
