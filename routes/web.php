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

Route::get('/tests','TestsController@index');

Route::resource('projects','ProjectsController');

//domain
Route::resource('domains','DomainsController');
Route::get('domains/create/{project}', 'DomainsController@create');
Route::post('/domains/preview', 'DomainsController@preview');
Route::post('/domains/extract', 'DomainsController@extract');

//link
Route::resource('links','LinksController');
Route::get('links/create/{domain}', 'LinksController@create');
//Route::get('links/preview/{link}', 'LinksController@preview');
Route::post('/links/preview', 'LinksController@preview');
Route::post('/links/applyselector', 'LinksController@applySelector');
Route::post('/links/extract', 'LinksController@extract');
Route::get('/links/download/{domain}', 'LinksController@download');


Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

