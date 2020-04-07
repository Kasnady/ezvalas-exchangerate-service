<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerNewExchangeRate extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::unprepared('
		CREATE TRIGGER tr_new_exchange_rate AFTER INSERT ON `exchange_rates` FOR EACH ROW
			BEGIN
				INSERT INTO exchange_rate_settings (
					`id`,
					`exchange_rate_id`,
					`created_by`, `updated_by`,
					`created_at`, `updated_at`)
				VALUES (
					UUID(),
					NEW.id,
					NEW.created_by, NEW.updated_by,
					CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
			END
		');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::unprepared('DROP TRIGGER `tr_new_exchange_rate`');
	}
}
