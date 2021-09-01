<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Access-Control-Allow-Headers: Accept, Authorization');

Route::get('readyMadeAnimations','Animation\AnimationController@readyMadeAnimations');
Route::get('googleFonts','ThirdParty\GoogleFontsController@all');
Route::get('giphy/{query}','ThirdParty\GiphyController@search');
Route::get('animation/{name}','Animation\AnimationController@get');

Route::get('seed/readyMadeAnimations','Seeding\ReadyMadeAnimationsSeedingController@seed');
