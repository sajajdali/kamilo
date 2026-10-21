<?php

use Illuminate\Http\Request;
Route::post("v1/test",function(Request $request){
	$test = array();
	if($request->isJson()){
		$test['status'] = false;
		$test['act'] = request()->input('act');
	}else{
		$test['status'] = false;
		$test['act'] = request()->input('act');
	}
	return json_encode($test,JSON_UNESCAPED_UNICODE);
});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function(Request $request) {
	return $request->user();
});
Route::middleware('auth:api')->get('v1/apiTest', function(Request $request) {
	$test = array();
	if($request->isJson()){
		$test['status'] = false;
		$test['act'] = request()->input('act');
	}else{
		$test['status'] = false;
		$test['act'] = request()->input('act');
	}
    $test['user_id'] = $request->user()->id;
	return json_encode($test,JSON_UNESCAPED_UNICODE);
});
