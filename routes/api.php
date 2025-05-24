<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/check-uid', [AbsensiController::class, 'checkUid']);
Route::post('/check-in', [AbsensiController::class, 'checkIn']);
