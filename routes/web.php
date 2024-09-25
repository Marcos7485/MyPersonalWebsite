<?php

use App\Http\Controllers\ContactController;
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




// Route::get('/email', [ContactController::class, 'email']);

