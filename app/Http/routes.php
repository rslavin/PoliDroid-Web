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

Route::get('/', 'HomeController@getIndex');
Route::get('pvdetector', 'AnalysisController@getConsistencyForm');
Route::post('pvdetector', 'AnalysisController@postconsistencyForm');
Route::get('source-analyzer', 'AnalysisController@getSourceAnalyzer');
Route::get('publications', 'HomeController@getPublications');
Route::get('tutorials/{type?}', 'HomeController@getTutorial');
Route::get("privacy", function(){
    return view('site.privacy');
});