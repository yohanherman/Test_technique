<?php

use App\Http\Controllers\V1\authController;
use App\Http\Controllers\V1\profilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', [authController::class, 'registerAdmin'])->name('post.register');

Route::get('/profils', [profilController::class, "getAllProfil"])->name('get.profils');
