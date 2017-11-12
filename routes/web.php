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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/data', 'HomeController@anyData')->name('datatables.data');
Route::get('/client-details/{client_id}', 'HomeController@getClientDetails')->name('admin.get.clientDetails');
Route::get('/reports', 'DataController@viewAllReports')->name('admin.get.allReports');
Route::get('/reports/all/data', 'DataController@allReportsData')->name('datatables.reports.data');
