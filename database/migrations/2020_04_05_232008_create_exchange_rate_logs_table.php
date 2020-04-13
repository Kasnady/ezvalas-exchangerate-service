<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRateLogsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exchange_rate_logs', function (Blueprint $table) {
			$table->bigIncrements('log_id');
			$table->uuid('id')->index();
			$table->unsignedTinyInteger('from_country_id')->index();
			$table->unsignedTinyInteger('to_country_id')->index();
			$table->decimal('buy_rate', 15, 7);
			$table->decimal('base_rate', 15, 7);
			$table->decimal('sell_rate', 15, 7);
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->softDeletes();
			$table->timestamps();
			$table->timeStamp('log_deleted_at')->nullable();
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
		Schema::dropIfExists('exchange_rate_logs');
	}
}
