<?php

namespace App\Models\Views;

use App\Models\ExchangeRateSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeRateWithCurrencyCodeView extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exchange_rate_with_currency_code_view';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Relation
     */

    public function setting()
    {
        return $this->hasOne(ExchangeRateSetting::class, 'exchange_rate_id');
    }
}
