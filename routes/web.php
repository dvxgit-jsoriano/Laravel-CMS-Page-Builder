<?php

use App\Http\Controllers\AssetLibraryController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('/');
Route::get('main', [MainController::class, 'index'])->name('main');

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/page-setup', function () {
    return view('page-setup');
});

Route::get('page-builder', [MainController::class, 'index_builder'])->name('page-builder');
Route::post('process-builder', [MainController::class, 'process_builder'])->name('process-builder');

Route::get('pages/{siteId}', [MainController::class, 'pages']);
Route::get('page-data/{id}', [MainController::class, 'pageData'])->name('pageData');
Route::post('create-block', [MainController::class, 'createBlock'])->name('createBlock');
Route::get('get-sites', [MainController::class, 'fetchSites'])->name('getSites');
Route::get('get-site-info/{siteId}', [MainController::class, 'getSiteInfo'])->name('getSiteInfo');
Route::post('create-site', [MainController::class, 'createSite'])->name('createSite');
Route::get('get-templates', [MainController::class, 'fetchTemplates'])->name('getTemplates');
Route::get('get-pages/{siteId}', [MainController::class, 'getPages'])->name('getPages');
Route::post('create-page', [MainController::class, 'createPage'])->name('createPage');
Route::post('create-block-field-group-item', [MainController::class, 'createBlockFieldGroupItem'])->name('createBlockFieldGroupItem');
Route::post('set-template-to-site', [MainController::class, 'setTemplateToSite'])->name('setTemplateToSite');
Route::post('update-block-positions', [MainController::class, 'updateBlockPositions'])->name('updateBlockPositions');
Route::delete('delete-block', [MainController::class, 'deleteBlock'])->name('deleteBlock');
Route::post('update-block', [MainController::class, 'updateBlock'])->name('updateBlock');
Route::delete('delete-block-field-group-items', [MainController::class, 'deleteBlockFieldGroupItems'])->name('deleteBlockFieldGroupItems');

Route::get('get-block-set', [MainController::class, 'getBlockSet'])->name('getBlockSet');

Route::post('upload-asset-library', [AssetLibraryController::class, 'uploadAssetLibrary'])->name('uploadAssetLibrary');
Route::get('get-asset-library', [AssetLibraryController::class, 'getAssetLibrary'])->name('getAssetLibrary');

Route::post('publish-page', [MainController::class, 'publishPage'])->name('publishPage');
// Display the page by slug
Route::get('/{slug}', [MainController::class, 'showPage']);
