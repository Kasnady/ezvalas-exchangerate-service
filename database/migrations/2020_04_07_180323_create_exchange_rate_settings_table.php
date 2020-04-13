<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ex_internal = config('database.services.internal');

        Schema::create('exchange_rate_settings', function (Blueprint $table) use ($ex_internal) {
            $table->uuid('id')->primary();
            $table->uuid('exchange_rate_id')->unique()->index();
            $table->decimal('multiply_amount')->default(1)
                ->comment('Destination Exchange Amount must be a multiply of this value');
            $table->decimal('min_sell_amount')->default(0);
            $table->decimal('max_sell_amount')->default(0);
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();

            $table->foreign('exchange_rate_id')->references('id')->on('exchange_rates');
            $table->foreign('created_by')->references('id')->on($ex_internal.'.users');
            $table->foreign('updated_by')->references('id')->on($ex_internal.'.users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rate_settings');
    }
}
