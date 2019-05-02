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

Route::get('/', 'ContestController@getRule');
Route::get('error', 'ContestController@getError');
Route::get('rule', 'ContestController@getRule');
Route::post('rule', 'ContestController@postRule');
Route::get('import', 'ContestController@getImport');
Route::post('import', 'ContestController@postImport');
Route::get('draw', 'ContestController@getDraw');
Route::post('draw', 'ContestController@postDraw');
Route::post('label', 'ContestController@postLabel');
Route::post('chartlog', 'ContestController@postChartLog');
Route::get('slope', 'ContestController@getSlope');
Route::get('draw-data', 'ContestController@getDrawData');

Route::get('groupregister', 'Auth\GroupRegister@showRegisterView')->name('groupregister');;
Route::post('groupregister', 'Auth\GroupRegister@register');

Route::get('changepassword', 'Auth\ChangePasswordController@showChangePasswordView')->name('changepassword');
Route::post('changepassword', 'Auth\ChangePasswordController@changePassword');

Route::get('alluser', 'Auth\AllUserController@showAllUserView')->name('alluser');

Auth::routes();

