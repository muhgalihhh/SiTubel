<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\DownloadController;
use App\Livewire\Home;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/downloadLampiranPersyaratan/{file}', [DownloadController::class, 'downloadFileLampiran'])->name('download.lampiranpersyaratan');

    Route::get('/downloadBerkasSetelahLulus/{file}', [DownloadController::class, 'downloadFileBerkasSetelahLulus'])->name('download.pemberkasansetelahlulus');

    Route::get('/downloadSurat/{file}', [DownloadController::class, 'downloadSurat'])->name('download.fileSurat');

    Route::get('/download-surat/{recordId}', [SuratController::class, 'createSuratPermohonanSatu'])->name('download.surat');
    Route::get('/download-surat-dua/{recordId}', [SuratController::class, 'createSuratPermohonanDua'])->name('download.suratDua');
    Route::get('/download-surat-dua-walkot/{recordId}', [SuratController::class, 'createSuratPermohonanDuaWalikota'])->name('download.suratDuaWalkot');
    Route::get('/download-surat-ttd-sekda/{recordId}', [SuratController::class, 'createSuratSekdaMengikutiSeleksi'])->name('download.suratIzinTtdSekda');
    Route::get('/download-surat-perintah-tubel-sekda/{recordId}', [SuratController::class, 'createSuratPerintahTubelSekda'])->name('download.suratPerintahTubelSekda');
    Route::get('/download-surat-perintah-tubel-walkot/{recordId}', [SuratController::class, 'createSuratPerintahTubelWalikota'])->name('download.suratPerintahTubelWalikota');
    Route::get('/lampiran/{file}', [DownloadController::class, 'previewLampiran'])->name('preview.lampiran');
    Route::get('/lampiran-lulus/{file}', [DownloadController::class, 'previewLampiranLulus'])->name('preview.lampiranLulus');
    Route::get('/lampiran-surat/{file}', [DownloadController::class, 'previewSurat'])->name('preview.lampiranSurat');
});

Route::get('/register', function () {
    return view('register');
})->name('register');
