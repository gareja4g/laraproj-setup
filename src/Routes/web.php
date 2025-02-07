<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;

Route::controller(ExampleController::class)
    ->prefix("/example")
    ->name("example")
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/list', 'getList')->name('.list');
        Route::get('/create', 'create')->name('.create');
        Route::post('/store', 'store')->name('.store');
        Route::get('/edit/{id}', 'edit')->name('.edit');
        Route::post('/update/{id}', 'update')->name('.update');
        Route::delete('/delete/{id}', 'destroy')->name('.delete');
    });
