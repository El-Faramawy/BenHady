<?php

use App\Http\Controllers\ApiDocsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('api_docs', [ApiDocsController::class, 'index'])->name('api.docs');
Route::get('api_docs.md', [ApiDocsController::class, 'raw'])->name('api.docs.raw');
Route::get('api-docs', fn () => redirect()->route('api.docs'));

