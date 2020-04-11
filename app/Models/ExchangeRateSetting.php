<?php

namespace App\Models;

use App\traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class ExchangeRateSetting extends Model
{
    use Uuids;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($exrSetting) {
        	LogHelper::addLogFrom($exrSetting, new ExchangeRateSettingLog);
        });
    }

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [

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

	public function exchangeRate()
	{
		return $this->belongsTo(ExchangeRate::class, 'exchange_rate_id', 'id');
	}
}
