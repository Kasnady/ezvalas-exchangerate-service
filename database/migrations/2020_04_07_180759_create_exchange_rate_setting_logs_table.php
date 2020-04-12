<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRateSettingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rate_setting_logs', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->uuid('id')->index();
            $table->uuid('exchange_rate_id')->index();
            $table->decimal('min_sell_amount');
            $table->decimal('max_sell_amount');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->timestamps();
            $table->timeStamp('logged_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rate_setting_logs');
    }
}
