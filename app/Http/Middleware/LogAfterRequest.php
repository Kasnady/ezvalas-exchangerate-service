<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;

final class LogAfterRequest
{
	private $logger;

	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);

		$TAG = md5(json_encode($request->all()).random_int(0, 9999));
		Log::info($TAG, ['initial_request'=> $request->getPathInfo() . ($request->getQueryString() ? ('?' . $request->getQueryString()) : '')]);
		Log::info($TAG, ['request'=> $request->all()]);
		Log::info($TAG, ['response'=>$response->getContent()]);

		return $response;
	}
}