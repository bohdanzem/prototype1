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

Route::get( '/', 'RListController@index' );

Route::post( 'upload', 'RListController@upload' );

Route::post( 'saveResults', 'RListController@saveResults' );

Route::post( 'getRList', 'RListController@getRList' );

Route::post( 'saveOrDownloadRList', 'RListController@saveOrDownloadRList' );

Route::post( 'downloadNewRList', 'RListController@downloadNewRList' );
Route::post( 'downloadNewRListWD', 'RListController@downloadNewRList' );

Route::post( 'exportResults', 'RListController@exportResults' );

Route::post( 'importResults', 'RListController@importResults' );

Route::post( 'downloadResultsBackup', 'RListController@downloadResultsBackup' );

Route::post( 'clearResultsBackup', 'RListController@clearResultsBackup' );
