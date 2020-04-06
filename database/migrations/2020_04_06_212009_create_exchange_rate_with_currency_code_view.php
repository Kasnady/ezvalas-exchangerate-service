<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRateWithCurrencyCodeView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ex_info = config('database.services.info');

        DB::statement("CREATE VIEW exchange_rate_with_currency_code_view AS
            SELECT
                ex_rate.*,
                from_c.currency_code AS from_currency_code,
                to_c.currency_code AS to_currency_code
            FROM exchange_rates ex_rate
                LEFT JOIN ".$ex_info.".countries from_c ON ex_rate.from_country_id = from_c.id
                LEFT JOIN ".$ex_info.".countries to_c ON ex_rate.to_country_id = to_c.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW exchange_rate_with_currency_code_view");
    }
}
