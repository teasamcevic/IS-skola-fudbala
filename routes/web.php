<?php

use App\Http\Controllers\Admin\ClanController as AdminClanController;
use App\Http\Controllers\Admin\ClanarinaController as AdminClanarinaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IzvestajController;
use App\Http\Controllers\Admin\NastupController as AdminNastupController;
use App\Http\Controllers\Admin\SelekcijaController as AdminSelekcijaController;
use App\Http\Controllers\Admin\TimController as AdminTimController;
use App\Http\Controllers\Admin\TrenerController as AdminTrenerController;
use App\Http\Controllers\Admin\TreningController as AdminTreningController;
use App\Http\Controllers\Admin\UtakmicaController as AdminUtakmicaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Roditelj\RoditeljController;
use App\Http\Controllers\Trener\DashboardController as TrenerDashboardController;
use App\Http\Controllers\Trener\NastupController as TrenerNastupController;
use App\Http\Controllers\Trener\TimController as TrenerTimController;
use App\Http\Controllers\Trener\TreningController as TrenerTreningController;
use App\Http\Controllers\Trener\UtakmicaController as TrenerUtakmicaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/dashboard', DashboardRedirectController::class)->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::resource('clanovi', AdminClanController::class)->except(['show']);
    Route::resource('treneri', AdminTrenerController::class)->except(['show']);
    Route::resource('selekcije', AdminSelekcijaController::class)->except(['show']);
    Route::resource('treninzi', AdminTreningController::class)->except(['show']);
    Route::resource('utakmice', AdminUtakmicaController::class)->except(['show']);
    Route::resource('timovi', AdminTimController::class)->except(['show']);
    Route::resource('napredak', AdminNastupController::class)->except(['show'])->parameters(['napredak' => 'nastup']);
    Route::resource('clanarine', AdminClanarinaController::class)->except(['show']);
    Route::get('izvestaji', IzvestajController::class)->name('izvestaji');
});

Route::middleware(['auth', 'role:trener'])->prefix('trener')->name('trener.')->group(function () {
    Route::get('/dashboard', [TrenerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/selekcija', [TrenerDashboardController::class, 'selekcija'])->name('selekcija');
    Route::get('/clanovi', [TrenerDashboardController::class, 'clanovi'])->name('clanovi');
    Route::post('/clanovi/{clan}/selekcija', [TrenerDashboardController::class, 'dodeliSelekciju'])->name('clanovi.dodeli-selekciju');
    Route::resource('treninzi', TrenerTreningController::class)->except(['show']);
    Route::resource('utakmice', TrenerUtakmicaController::class)->except(['show']);
    Route::resource('timovi', TrenerTimController::class)->except(['show']);
    Route::resource('napredak', TrenerNastupController::class)->except(['show'])->parameters(['napredak' => 'nastup']);
});

Route::middleware(['auth', 'role:clan_roditelj'])->prefix('roditelj')->name('roditelj.')->group(function () {
    Route::get('/dashboard', [RoditeljController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [RoditeljController::class, 'profil'])->name('profil');
    Route::get('/treninzi', [RoditeljController::class, 'treninzi'])->name('treninzi');
    Route::get('/utakmice', [RoditeljController::class, 'utakmice'])->name('utakmice');
    Route::get('/napredak', [RoditeljController::class, 'napredak'])->name('napredak');
    Route::get('/clanarine', [RoditeljController::class, 'clanarine'])->name('clanarine');
});
