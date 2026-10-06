<?php

use App\Http\Controllers\Api\MasterKirimanController;
use App\Http\Controllers\Api\RiwayatInputController;
use Illuminate\Support\Facades\Route;

// Tempel isi file ini ke routes/api.php pada instalasi Laravel yang sudah ada,
// atau include langsung. Sesuaikan middleware/prefix (mis. auth:sanctum) sesuai
// kebutuhan proyek.

Route::apiResource('master-kiriman', MasterKirimanController::class)
    ->only(['index', 'store', 'update', 'destroy']);

Route::apiResource('riwayat-input', RiwayatInputController::class)
    ->only(['index', 'show', 'store', 'destroy']);
