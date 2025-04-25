<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('page-builder');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/page-builder', function () {
    return view('page-builder');
});
