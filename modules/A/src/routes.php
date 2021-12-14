<?php
use Illuminate\Support\Facades\Route;

Route::get('test', 'AController@index');
Route::get('test/{id}', 'AController@edit');
