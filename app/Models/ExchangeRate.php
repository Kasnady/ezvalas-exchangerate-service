<?php

namespace App\Models;

use App\traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use Uuids;

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
}
