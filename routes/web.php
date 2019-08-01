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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ladder', 'pageController@ladder');
Route::get('/ladder', 'LadderController@index');

Route::post('/ajaxupdate', 'LadderController@ajaxPost');
Route::post('/ajaxsearch', 'LadderController@ajaxSearch');

Route::get('/profile/{account}', 'ProfileController@index');

Route::get('/api', 'ProfileController@GetChartData');
