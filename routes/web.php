<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusyrifController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\WaliController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->to(AuthController::redirectPath(auth()->user()->role));
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/ganti-password', [AuthController::class, 'showChangePassword'])->name('password.edit');
    Route::post('/ganti-password', [AuthController::class, 'changePassword'])->name('password.update');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    });

    Route::middleware('role:musyrif')->prefix('musyrif')->name('musyrif.')->group(function () {
        Route::get('/dashboard', [MusyrifController::class, 'dashboard'])->name('dashboard');
        Route::get('/santri/{santriId}', [MusyrifController::class, 'santriDetail'])->name('santri.detail');
    });

    Route::middleware('role:santri')->prefix('santri')->name('santri.')->group(function () {
        Route::get('/dashboard', [SantriController::class, 'dashboard'])->name('dashboard');
    });

    Route::middleware('role:wali')->prefix('wali')->name('wali.')->group(function () {
        Route::get('/dashboard', [WaliController::class, 'dashboard'])->name('dashboard');
        Route::get('/anak/{santriId}', [WaliController::class, 'childDetail'])->name('child.detail');
    });
});
