<?php

use Illuminate\Http\Request;

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

Route::get('match', 'MatchController@matches')->name('matches');
Route::get('match/{id}', 'MatchController@match')->name('match');
Route::put('match/{id}', 'MatchController@move')->name('move');
Route::post('match', 'MatchController@create')->name('create_match');
Route::delete('match/{id}', 'MatchController@delete')->name('delete_match');
