<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exchange_rates', function (Blueprint $table) {
			$table->uuid('id');
			$table->unsignedTinyInteger('from_country_id')->index();
			$table->unsignedTinyInteger('to_country_id')->index();
			$table->decimal('buy_rate', 15, 7);
			$table->decimal('base_rate', 15, 7);
			$table->decimal('sell_rate', 15, 7);
			$table->uuid('created_by');
			$table->uuid('updated_by');
			$table->softDeletes();
			$table->timestamps();

			$table->index(['from_country_id', 'to_country_id']);

			$table->unique(['from_country_id', 'to_country_id']);

			$table->foreign('from_country_id')->references('id')->on('ex_info.countries');
			$table->foreign('to_country_id')->references('id')->on('ex_info.countries');

			$table->foreign('created_by')->references('id')->on('ex_internal.users');
			$table->foreign('updated_by')->references('id')->on('ex_internal.users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('exchange_rates');
	}
}
