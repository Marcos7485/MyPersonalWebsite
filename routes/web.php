<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\IqAdminAuthController;
use App\Http\Controllers\IqAdminDashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReviewsController::class, 'index'])->name('index');
Route::get('/language/{id}', [LanguageController::class, 'LanguageSelector']);
Route::post('/contact', [ContactController::class, 'contactForm']);

// Reviews
Route::get('/reviewCliente/{hash}', [ReviewsController::class, 'ReviewCliente'])->name('leave-review');
Route::post('/review', [ReviewsController::class, 'reviewForm']);

// Cliente
Route::get('/cliente/welcome', [ProjectController::class, 'welcome']);
Route::post('/cliente/load', [ProjectController::class, 'load']);
Route::get('/cliente/panel/{project}', [ProjectController::class, 'panel'])->name('cliente.panel');

// iQ Athletic admin
Route::prefix('iqathletic')->name('iqathletic.')->group(function () {
    Route::get('/login', [IqAdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [IqAdminAuthController::class, 'login'])
        ->middleware('throttle:8,1')
        ->name('login.submit');
    Route::post('/logout', [IqAdminAuthController::class, 'logout'])
        ->middleware('auth:iqadmin')
        ->name('logout');
    Route::get('/dashboard', [IqAdminDashboardController::class, 'show'])
        ->middleware('auth:iqadmin')
        ->name('dashboard');
});
