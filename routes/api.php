<?php

use App\Http\Controllers\V1\AdminController;
use App\Http\Controllers\V1\authController;
use App\Http\Controllers\V1\profilController;
use App\Http\Middleware\Administrator;
use Illuminate\Support\Facades\Route;

// Auth routes when already connected
Route::prefix('admin/auth')->middleware(Administrator::class)->group(function () {
    Route::post('/refresh', [authController::class, 'refresh'])->name('post.refresh');
    Route::post('/logout', [authController::class, 'logout'])->name('post.logout');
});

// Auth public routes
Route::prefix('admin/auth')->group(function () {
    Route::post('/register', [authController::class, 'registerAdmin'])->name('post.register');
    Route::post('/login', [authController::class, 'login'])->name('post.login');
});

//  Group of routes that allow the Admin to READ, CREATE, UPDATE, DELETE a profil in condition to be connected
Route::prefix('admin')->middleware(Administrator::class)->group(function () {
    Route::get('/profiles', [AdminController::class, "getAllProfile"])->name('admin.get.profiles');
    Route::post('/profiles', [AdminController::class, "createProfile"])->name('post.profiles');
    Route::put('/profiles/{id}', [AdminController::class, "updateProfile"])->name('put.profiles')->where('id', '[0-9]+');
    Route::get('/profiles/{id}', [AdminController::class, "getSingleProfile"])->name('admin.get.profile')->where('id', '[0-9]+');
    Route::delete('/profiles/{id}', [AdminController::class, "deleteProfile"])->name('delete.profiles')->where('id', '[0-9]+');
});

// Public Routes
Route::get('/profiles', [profilController::class, "getAllProfile"])->name('get.profiles');
