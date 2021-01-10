<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
Route::get('/login/verify', 'Auth\VerificationController@index')->name('login.verify');
Route::post('/login/verify', 'Auth\VerificationController@verify')->name('login.verify');
//challenge routs:
Route::get('/challenge/invite', 'Challenge\ChallengeController@invite')->name('challenge.invite')->middleware('verified');
Route::post('/challenge/invite', 'Challenge\ChallengeController@getUser')->name('challenge.invite')->middleware('verified');
//challenge start
Route::get('/challenge/start', 'Challenge\ChallengeController@quizStart')->name('challenge.start')->middleware('verified');
Route::post('/challenge/start', 'Challenge\ChallengeController@quizRun')->name('challenge.run')->middleware('verified');
Route::get('/challenge/answer/{id}', 'Challenge\ChallengeController@answerLoad')->name('challenge.load')->middleware('verified');
Route::post('/challenge/answer', 'Challenge\ChallengeController@answerStart')->name('challenge.answer')->middleware('verified');
Route::get('/groupChallenge/start', 'Challenge\ChallengeController@prepGroup')->name('group.prep')->middleware('verified');
Route::post('/groupChallenge/start', 'Challenge\ChallengeController@createGroup')->name('group.create')->middleware('verified');
//Route::post('/challenge/getUser', 'Challenge\ChallengeController@getUser')->name('challenge.getUser')->middleware('verified');
Route::get('result/{first}', 'Challenge\ResultController@index')->name('challenge.result')->middleware('verified');
Route::post('result/likes', 'Challenge\ResultController@likes')->name('challenge.likes')->middleware('verified');
Route::get('group/{id}', 'GroupController@index')->name('gruop.show')->middleware('verified');
Route::post('user/add', 'GroupController@addUser')->name('add.user')->middleware('verified');
//test
Route::get('/test', 'TestController@index')->name('test');
Route::get('/shared/{test}', 'Challenge\ShareController@show')->name('share');
Route::post('/result', 'Challenge\ShareController@setUrl')->name('setUrl')->middleware('verified');
Route::resource('admin', 'AdminController');
Route::get('users', 'AdminController@users');
Route::get('errors', 'AdminController@errors');
Route::get('sms', 'AdminController@sms');
