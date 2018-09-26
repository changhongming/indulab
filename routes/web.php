<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$prefix_url = 'test-project/public/';
Route::get('/', 'ContestController@getRule');
Route::get('error', 'ContestController@getError');
Route::get('rule', 'ContestController@getRule');
Route::post('rule', 'ContestController@postRule');
Route::get('import', 'ContestController@getImport');
Route::post('import', 'ContestController@postImport');
Route::get('draw', 'ContestController@getDraw');
Route::post('draw', 'ContestController@postDraw');

Route::get($prefix_url.'error', 'ContestController@getError');
Route::get($prefix_url.'rule', 'ContestController@getRule');
Route::post($prefix_url.'rule', 'ContestController@postRule');
Route::get($prefix_url.'import', 'ContestController@getImport');
Route::post($prefix_url.'import', 'ContestController@postImport');
Route::get($prefix_url.'draw', 'ContestController@getDraw');
Route::post($prefix_url.'draw', 'ContestController@postDraw');