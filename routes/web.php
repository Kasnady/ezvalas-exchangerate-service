<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/list[/{sortBy}/{sortType}]',			'ExchangeRateController@list');
$router->post('/update',							'ExchangeRateController@updateRate');

$router->get('/version', function () use ($router) {
	// Framework Version
	return $router->app->version();
});

$router->group(['prefix' => '{id}'], function () use ($router)
{
	$router->get('/',				'ExchangeRateController@findRate');

	$router->group(['prefix' => 'setting'], function() use ($router)
	{
		$router->get('/',			'ExchangeRateSettingController@getSetting');
		$router->post('/',			'ExchangeRateSettingController@updateSetting');
	});
});