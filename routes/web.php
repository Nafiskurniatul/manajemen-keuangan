<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    AccountController,
    TransactionController,
    JurnalController,
    BukuBesarController,
    NeracaController,
    LabaRugiController,
    ArusKasController
};

// Halaman awal (opsional)
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Jika kamu belum bikin register, bisa hapus 2 baris ini
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

// Semua route di bawah ini hanya bisa diakses oleh user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);
    Route::patch('/transactions/{reference_id}/post', [TransactionController::class, 'post'])->name('transactions.post');

    Route::get('/jurnal', [JurnalController::class, 'index'])->name('jurnal.index');
    Route::get('/jurnal/{reference_id}', [JurnalController::class, 'show'])->name('jurnal.show');
    Route::get('/jurnal/{reference_id}/download', [JurnalController::class, 'download'])->name('jurnal.download');

    Route::get('/buku-besar', [BukuBesarController::class, 'index'])->name('buku-besar.index');
    Route::get('/buku-besar/cetak', [BukuBesarController::class, 'cetak'])->name('buku-besar.cetak');
    Route::get('/buku-besar/excel', [BukuBesarController::class, 'excel'])->name('buku-besar.excel');

    Route::get('/laporan/neraca', [NeracaController::class, 'index'])->name('neraca.index');
    Route::get('/neraca/print', [NeracaController::class, 'print'])->name('neraca.print');

    Route::get('/laporan/laba-rugi', [LabaRugiController::class, 'index'])->name('laba_rugi.index');
    Route::get('/laba-rugi/print', [LabaRugiController::class, 'print'])->name('laba-rugi.print');

    Route::get('/arus-kas', [ArusKasController::class, 'index'])->name('arus-kas.index');
    Route::get('/arus-kas/print', [ArusKasController::class, 'print'])->name('arus-kas.print');
});
