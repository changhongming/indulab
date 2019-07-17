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

Route::get('/', 'ContestController@getImport');
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

Route::match(['put', 'patch'], 'changepassword', 'Auth\ChangePasswordController@changePassword');

Route::get('alluser', 'Auth\AllUserController@showAllUserView')->name('alluser');

Route::resource('users', 'UserController', ['parameters' => [
    'users' => 'user_id',
]]);

Auth::routes();

Route::post('question', 'QuizEditorContorller@postQuestion')->name('questions.create');
Route::delete('question/{id}', 'QuizEditorContorller@deleteQuestion')->name('questions.destroy');
Route::get('question', 'QuizEditorContorller@getQuestions');
Route::get('quiz', 'QuizEditorContorller@getQuiz');
Route::get('quizs', 'QuizEditorContorller@getQuizes')->name('quizs');
Route::get('quizs/edit', 'QuizEditorContorller@getQuestionEditorView')->name('quizs.edit');
Route::get('quizs/create', 'QuizEditorContorller@getQuestionCreateView')->name('quizs.create');
Route::post('quizs/create', 'QuizEditorContorller@postTest')->name('quizs.store');
Route::delete('quizs/{id}', 'QuizEditorContorller@deleteTest')->name('quizs.destroy');
Route::get('quizs/{id}/edit', 'QuizEditorContorller@getEditTestView')->name('quizs.editquiz');
Route::put('quizs', 'QuizEditorContorller@changeTest')->name('quizs.change');

Route::get('test/{id}', 'QuizTestContorller@getQuestions');
