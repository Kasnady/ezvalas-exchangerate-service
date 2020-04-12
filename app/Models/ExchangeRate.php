<?php

namespace App\Models;

use App\traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeRate extends Model
{
	use SoftDeletes, Uuids;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($exchangeRate) {
        	LogHelper::addLogFrom($exchangeRate, new ExchangeRateLog);
        });
    }

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
	 * Relation
	 */

	public function setting()
	{
		return $this->hasOne(ExchangeRateSetting::class, 'id', 'exchange_rate_id');
	}
}
