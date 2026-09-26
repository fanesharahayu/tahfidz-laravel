<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusyrifController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\WaliController;
use App\Http\Controllers\ReportController;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->to(AuthController::redirectPath(auth()->user()->role));
    }

    try {
        $stats = [
            'jumlahSantri' => Santri::count(),
            'jumlahSetoran' => Setoran::count(),
            'jumlahMusyrif' => User::where('role', 'musyrif')->count(),
            'jumlahWali' => User::where('role', 'wali')->count(),
        ];
    } catch (\Throwable $e) {
        $stats = ['jumlahSantri' => 0, 'jumlahSetoran' => 0, 'jumlahMusyrif' => 0, 'jumlahWali' => 0];
    }

    return view('welcome', $stats);
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::delete('/profil/sesi-lain', [ProfilController::class, 'destroyOthers'])->name('profil.sessions.destroyOthers');
    Route::get('/ganti-password', [AuthController::class, 'showChangePassword'])->name('password.edit');
    Route::post('/ganti-password', [AuthController::class, 'changePassword'])->name('password.update');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('users.create');
        Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'usersEdit'])->name('users.edit');
        Route::match(['put', 'patch'], '/users/{id}', [AdminController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'usersDestroy'])->name('users.destroy');
        Route::get('/santri', [AdminController::class, 'santriIndex'])->name('santri.index');
        Route::get('/santri/create', [AdminController::class, 'santriCreate'])->name('santri.create');
        Route::post('/santri', [AdminController::class, 'santriStore'])->name('santri.store');
        Route::get('/santri/{id}/edit', [AdminController::class, 'santriEdit'])->name('santri.edit');
        Route::match(['put', 'patch'], '/santri/{id}', [AdminController::class, 'santriUpdate'])->name('santri.update');
        Route::delete('/santri/{id}', [AdminController::class, 'santriDestroy'])->name('santri.destroy');
        Route::get('/targets', [AdminController::class, 'targetIndex'])->name('targets.index');
        Route::get('/target/create', [AdminController::class, 'targetCreate'])->name('target.create');
        Route::post('/target', [AdminController::class, 'targetStore'])->name('target.store');
        Route::get('/target/{id}/edit', [AdminController::class, 'targetEdit'])->name('target.edit');
        Route::match(['put', 'patch'], '/target/{id}', [AdminController::class, 'targetUpdate'])->name('target.update');
        Route::delete('/target/{id}', [AdminController::class, 'targetDestroy'])->name('target.destroy');
        Route::get('/wali-links', [AdminController::class, 'waliLinksIndex'])->name('wali-links.index');
        Route::get('/wali-link/create', [AdminController::class, 'waliLinksCreate'])->name('wali-links.create');
        Route::post('/wali-link', [AdminController::class, 'waliLinksStore'])->name('wali-links.store');
        Route::get('/wali-link/{id}/edit', [AdminController::class, 'waliLinksEdit'])->name('wali-links.edit');
        Route::match(['put', 'patch'], '/wali-link/{id}', [AdminController::class, 'waliLinksUpdate'])->name('wali-links.update');
        Route::delete('/wali-link/{id}', [AdminController::class, 'waliLinksDestroy'])->name('wali-links.destroy');
        Route::post('/setoran', [AdminController::class, 'setoranStore'])->name('setoran.store');
        Route::get('/setoran', [AdminController::class, 'setoranIndex'])->name('setoran.index');
        Route::get('/setoran/create', [AdminController::class, 'setoranCreate'])->name('setoran.create');
        Route::get('/setoran/{id}/edit', [AdminController::class, 'setoranEdit'])->name('setoran.edit');
        Route::match(['put', 'patch'], '/setoran/{id}', [AdminController::class, 'setoranUpdate'])->name('setoran.update');
        Route::delete('/setoran/{id}', [AdminController::class, 'setoranDestroy'])->name('setoran.destroy');
        Route::get('/reports/tahfidz/{santriId}', [ReportController::class, 'downloadTahfidzReport'])->name('reports.tahfidz');
        Route::get('/reports/all-tahfidz', [ReportController::class, 'downloadAllTahfidzReports'])->name('reports.all-tahfidz');
    });

    Route::middleware('role:musyrif')->prefix('musyrif')->name('musyrif.')->group(function () {
        Route::get('/dashboard', [MusyrifController::class, 'dashboard'])->name('dashboard');
        Route::get('/binaan', [MusyrifController::class, 'binaanIndex'])->name('binaan.index');
        Route::get('/setoran', [MusyrifController::class, 'setoranIndex'])->name('setoran.index');
        Route::get('/targets', [MusyrifController::class, 'targetIndex'])->name('targets.index');
        Route::post('/target', [MusyrifController::class, 'targetStore'])->name('target.store');
        Route::get('/target/{id}/edit', [MusyrifController::class, 'targetEdit'])->name('target.edit');
        Route::put('/target/{id}', [MusyrifController::class, 'targetUpdate'])->name('target.update');
        Route::delete('/target/{id}', [MusyrifController::class, 'targetDestroy'])->name('target.destroy');
        Route::post('/santri', [MusyrifController::class, 'santriStore'])->name('santri.store');
        Route::get('/santri/unassigned', [MusyrifController::class, 'unassigned'])->name('santri.unassigned');
        Route::post('/santri/{santriId}/setoran', [MusyrifController::class, 'setoranStore'])->name('setoran.store');
        Route::get('/setoran/{id}/edit', [MusyrifController::class, 'setoranEdit'])->name('setoran.edit');
        Route::post('/santri/{santriId}/assign', [MusyrifController::class, 'assign'])->name('santri.assign');
        Route::get('/santri/{santriId}', [MusyrifController::class, 'santriDetail'])->name('santri.detail');
        Route::put('/setoran/{id}', [MusyrifController::class, 'setoranUpdate'])->name('setoran.update');
        Route::delete('/setoran/{id}', [MusyrifController::class, 'setoranDestroy'])->name('setoran.destroy');
        Route::get('/wali', [MusyrifController::class, 'waliLinksIndex'])->name('wali.index');
        Route::post('/wali-link', [MusyrifController::class, 'waliLinkStore'])->name('wali-link.store');
        Route::delete('/wali-link/{id}', [MusyrifController::class, 'waliLinkDestroy'])->name('wali-link.destroy');
    });

    Route::middleware('role:santri')->prefix('santri')->name('santri.')->group(function () {
        Route::get('/dashboard', [SantriController::class, 'dashboard'])->name('dashboard');
    });

    Route::middleware('role:wali')->prefix('wali')->name('wali.')->group(function () {
        Route::get('/dashboard', [WaliController::class, 'dashboard'])->name('dashboard');
        Route::get('/anak/{santriId}', [WaliController::class, 'childDetail'])->name('child.detail');
    });
});
