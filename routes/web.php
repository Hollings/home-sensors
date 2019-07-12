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
// Route::get('/combat', function () {
//  return view('combat');
// });

Route::get('/', function () {
    return "laravel";
});

Route::get('/data', 'DatumController@index');
Route::post('/data', 'DatumController@saveData');