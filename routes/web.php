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

/*Route::get('/', function () {
    return view('welcome');
});*/

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('me/validation/signup/{key}','Signup\SignupController@validate_signup');
Route::get('me/validation/update/{key}','User\UserController@validate_updateprofile');
Route::get('me/validation/deactivate/{key}','User\UserController@validate_deactivate');
//Route::get('payment/{plan}/execute/','User\UserController@payment_execute');
