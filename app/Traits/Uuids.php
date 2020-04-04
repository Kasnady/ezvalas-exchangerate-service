<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait Uuids
{

	/**
	 * Boot function from laravel.
	 */
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			$model->{$model->getKeyName()} = (String) Str::uuid();
		});
	}

	public function getIncrementing()
	{
		return false;
	}

	public function getKeyType()
	{
		return 'string';
	}
}