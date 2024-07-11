<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return json_ok(null, 200, 'Welcome to API');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
