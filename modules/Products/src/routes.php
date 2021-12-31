<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'ProductController@index');
Route::post('/', 'ProductController@insert');
Route::get('/{id}', 'ProductController@detail');
Route::put('/{id}', 'ProductController@update');
Route::delete('/{id}', 'ProductController@delete');

Route::post('/{id}/upload',"ProductController@uploadMedia");
Route::post('/delete-image',"ProductController@deleteImage");
Route::post('/set-image-base',"ProductController@setImagebase");
