<?php

use App\Http\Controllers\AbsensiController;
use App\Livewire\Example\Example;
use App\Livewire\MasterData\Mqtt;
use App\Livewire\Profile\Profile;
use App\Livewire\MasterData\Kelas;
use App\Livewire\MasterData\Jadwal;
use App\Livewire\MasterData\Absensi;
use App\Livewire\Dashboard\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\MasterData\Mahasiswa;
use App\Livewire\Control\User as ControlUser;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile', Profile::class);
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/example', Example::class);
    Route::get('/control-user', ControlUser::class);
    Route::get('/kelas', Kelas::class)->name('kelas');
    Route::get('/mahasiswa', Mahasiswa::class)->name('mahasiswa');
    Route::get('/jadwal', Jadwal::class)->name('jadwal');
    Route::get('/mqtt', Mqtt::class)->name('mqtt');
    Route::get('/absensi', Absensi::class)->name('absensi');
});

Route::group(['middleware' => ['auth', 'role:user']], function () {});
require __DIR__ . '/auth.php';
