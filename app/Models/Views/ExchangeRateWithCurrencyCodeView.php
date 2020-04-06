<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class ExchangeRateWithCurrencyCodeView extends Model
{
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
}
