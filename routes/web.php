<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/videos', 'VideoController@index');
Route::get('/videos/category/{id}', 'VideoController@index_categoryid');
Route::post('/videos/search', 'VideoController@index_search');
Route::get('/videos/manual_download', 'VideoController@manual_download');
Route::get('/videos/check_year', 'VideoController@check_year');
Route::get('/admin', function () {
    return view('admin');
});
Route::get('/admin/mastercategory', 'AdminController@index_mastercategory');
Route::get('/admin/mastercategory/create', 'AdminController@create_mastercategory');
Route::get('/admin/mastercategory/{id}', 'AdminController@show_mastercategory');
Route::post('/admin/mastercategory/update', 'AdminController@update_mastercategory');
Route::post('/admin/mastercategory/store', 'AdminController@store_mastercategory');

Route::get('/admin/video', 'AdminController@index_video');
Route::get('/admin/video/create', 'AdminController@create_video');
Route::get('/admin/video/{id}', 'AdminController@show_video');
Route::post('/admin/video/update', 'AdminController@update_video');
Route::post('/admin/video/store', 'AdminController@store_video');
