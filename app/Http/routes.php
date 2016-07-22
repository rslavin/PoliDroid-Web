<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'AnalysisController@getConsistencyForm');
Route::post('pvdetector', 'AnalysisController@postconsistencyForm');
//Route::get('test', function(){
//   Mail::send('emails.test', ['test' => 'test2'], function($m){
//       $m->from('donotreply2@polidroid.org', 'PoliDroid2');
//       $m->to('rocky.slavin@gmail.com')->subject('Test');
//   });
//});
