<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::post('/signup', 'AuthController@signup');
    Route::post('/login', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/user', 'AuthController@user');
    });
});

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'UserController@index');
    Route::post('/{id}', 'UserController@update')->where('id', '[0-9]+');;
    Route::post('/add-permission', 'UserController@addPermission');
});

Route::group(['prefix' => 'permission', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'PermissionController@index');
    Route::get('/{id}', 'PermissionController@detail');
    Route::post('/', 'PermissionController@insert');
    Route::put('/{id}', 'PermissionController@update');
    Route::delete('/{id}', 'PermissionController@delete');
});

Route::group(['prefix' => 'role', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'RoleController@index');
    Route::get('/{id}', 'RoleController@detail');
    Route::post('/', 'RoleController@insert');
    Route::put('/{id}', 'RoleController@update');
    Route::delete('/{id}', 'RoleController@delete');

    Route::post('/add-permission', 'RoleController@addPermission');
});
