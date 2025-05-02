<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('/');
Route::get('main', [MainController::class, 'index'])->name('main');

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/page-builder', function () {
    return view('page-builder');
});

Route::get('pages/{siteId}', [MainController::class, 'pages']);
Route::get('page-data/{id}', [MainController::class, 'pageData']);
