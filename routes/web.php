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


Route::prefix('admlogin')->group(function () {
    Route::get('index', 'Admlogin\AdmloginController@index')->name('Admlogin_index');
    Route::post('index/create', 'Admlogin\AdmloginController@create')->name('Admlogin_create');
    Route::put('index/edit', 'Admlogin\AdmloginController@edit')->name('Admlogin_edit');
    Route::delete('index/delete', 'Admlogin\AdmloginController@delete')->name('Admlogin_delete');
});

