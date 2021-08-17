<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Access-Control-Allow-Headers: Accept, Authorization');

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => ['auth:api','throttle:110,2'] ], function(){

  Route::post('me/profile/update','User\UserController@updateprofile');
  Route::get('me/profile/deactivate','User\UserController@deactivate');
  Route::get('me/fetch/readymateanimations','User\UserController@readymateanimations');

  Route::get('/me', 'User\UserController@user');
  Route::get('/me/elements','User\UserController@elements');
  Route::get('/me/notifications','User\UserController@notifications');
  Route::get('/me/animations','User\UserController@animations');
  Route::get('/me/logins','User\UserController@logins');
  Route::get('/me/activity','User\UserController@activity');
  Route::get('/me/payments','User\UserController@payments');
  Route::get('/me/tickets','User\UserController@tickets');
  Route::get('/me/ticket/{ticketid}/replies','User\TicketController@replies');
  Route::get('/me/metadata','User\UserController@metadata');
  Route::get('/me/storage','User\UserController@storage');
  Route::get('/me/media','User\UserMediaController@all');
  Route::get('/me/elementStyles/{elementType}','User\ElementStyleController@all');
  
  Route::post('/me/check/referralcode','User\UserController@referralcode');

  Route::post('/me/animation/add','User\AnimationController@add');
  Route::post('/me/animation/update','User\AnimationController@update');
  Route::post('/me/animation/delete','User\AnimationController@delete');

  Route::post('/me/element/add','User\ElementController@add');
  Route::post('/me/element/update','User\ElementController@update');
  Route::post('/me/element/delete','User\ElementController@delete');

  Route::get('/me/animations/{name}','Storage\AnimationsController@animation');

  Route::post('/me/tickets/add','User\TicketController@new');
  Route::post('/me/tickets/reply','User\TicketController@reply');

  Route::post('/me/payment/verify','User\UserController@verify_sale');
  Route::post('/me/payment/{plan}/{codetype}/{code}/execute/','User\UserController@payment_execute');
  Route::post('/me/payment/create','User\UserController@payment_create');
  Route::post('/me/payment/info','User\UserController@sale_info');

  Route::get('/giphy/{keyword}','ThirdParty\GiphyController@gifs');
  Route::get('/unsplash/{keyword}','ThirdParty\UnsplashController@images');
  Route::get('/pixabay/{keyword}','ThirdParty\PixabayController@images');
  Route::get('/youtube/videos/{keyword}','ThirdParty\YoutubeController@videos');
  Route::get('/youtube/video/{id}','ThirdParty\YoutubeController@videoMeta');
  Route::get('/google/fonts','ThirdParty\GoogleFontsController@fonts');

  Route::post('/media/upload','User\UserMediaController@upload');
  Route::post('/media/delete','User\UserMediaController@delete');
  Route::post('/media/update','User\UserMediaController@update');

  //---------------------------- Admin Routes -----------------------------

  Route::group(['middleware' => ['admin','throttle:110,2'] ], function(){

  Route::get('/admin/users','User\UserController@admin_users');
  Route::get('/admin/stats','User\UserController@admin_stats');

  Route::get('/admin/search/users/count/{keyword}/{field}','User\UserController@admin_search_users_count');
  Route::get('/admin/search/users/{keyword}/{field}','User\UserController@admin_search_users');

  Route::post('/admin/user/general','User\UserController@admin_user_general');
  Route::post('/admin/user/histories','User\UserController@admin_user_histories');
  Route::post('/admin/user/storage','User\UserController@admin_user_storage');

  Route::get('/admin/live','User\UserController@admin_live');

  //------------------------------------------------------------------------

  });

});

Route::get('/v2/style/{name}/{key}','Developers\DeveloperApiController@style');

//Route::get('me/validation/update/{key}','User\UserController@validate_updateprofile');

Route::get('/ip/{ip}','ThirdParty\IpStackController@ipinfo');

Route::get('/blogs','Blog\BlogController@blogs');

Route::post('/admin/login','Login\LoginController@admin_login');
Route::post('/login','Login\LoginController@login');
Route::post('/signup','Signup\SignupController@signup');

Route::post('/v1/oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');


Route::get("/run","User\ElementStyleController@addCheckBoxStyles");

//------------------------------------

Route::get('/animals/{count}','Animals\AnimalController@randomAnimals');
Route::post('/animals/add','Animals\AnimalController@addAnimal');

Route::get('/assets/{folder}/{type}/{file}','User\UserMediaController@media');