<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\JadwalKonsulController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PsikologController;





// Public routes
Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/jadwalkonsul', [JadwalKonsulController::class, 'index'])->name('jadwalkonsul.index');
Route::post('/jadwalkonsul', [JadwalKonsulController::class, 'store'])->name('jadwalkonsul.store');
Route::get('/user/jadwal', [JadwalKonsulController::class, 'showUserJadwal'])->name('user.jadwal');
// Route untuk update jadwal
Route::put('/jadwalkonsul/{id}', [JadwalKonsulController::class, 'update'])->name('jadwalkonsul.update');
Route::get('/jadwalkonsul/{id}/edit', [JadwalKonsulController::class, 'showUpdateForm'])->name('jadwalkonsul.edit');



Route::get('/booking', [JadwalKonsulController::class, 'index'])->name('booking');
Route::get('/nota/{id}', [PembelianController::class, 'showNota'])->name('nota');

// Rute untuk pembayaran
Route::get('payment/upload/{id}', [PembayaranController::class, 'showPaymentForm'])->name('payment.form');
Route::post('payment/upload/{id}', [PembayaranController::class, 'store'])->name('payment.store');


Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('admin.loginForm');
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');
    Route::get('dashboard', [AdminController::class, 'showDashboard'])->middleware('auth:admin')->name('admin.dashboard');
    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');


    Route::middleware('auth:admin')->group(function () {
        Route::get('/manage-users', [AdminController::class, 'showManageUsers'])->name('admin.manage_users');
        Route::get('/edit-user/{id_user}', [AdminController::class, 'editUser'])->name('admin.edit_user');
        Route::put('/update-user/{id_user}', [AdminController::class, 'updateUser'])->name('admin.update_user');
        Route::delete('/delete-user/{id_user}', [AdminController::class, 'deleteUser'])->name('admin.delete_user');

        Route::get('/psikologs', [AdminController::class, 'managePsikologs'])->name('admin.manage_psikologs');
        Route::get('/psikolog/{id}/edit', [AdminController::class, 'editPsikolog'])->name('admin.edit_psikolog');
        Route::put('/psikolog/{id}/update', [AdminController::class, 'updatePsikolog'])->name('admin.update_psikolog');
        Route::delete('/psikolog/{id}', [AdminController::class, 'deletePsikolog'])->name('admin.delete_psikolog');

        // Routes for managing paket
        Route::get('/pakets', [AdminController::class, 'managePakets'])->name('admin.manage_pakets');
        Route::get('/pakets/create', [AdminController::class, 'createPaket'])->name('admin.create_paket');
        Route::post('/pakets', [AdminController::class, 'store'])->name('admin.store_paket');
        Route::get('/pakets/{id}/edit', [AdminController::class, 'editPaket'])->name('admin.edit_paket');
        Route::put('/pakets/{id}', [AdminController::class, 'updatePaket'])->name('admin.update_paket');
        Route::delete('/pakets/{id}', [AdminController::class, 'deletePaket'])->name('admin.delete_paket');

         // Route untuk manage jadwal konsultasi
         Route::get('/manage_jadwalkonsul', [AdminController::class, 'manageJadwalKonsul'])->name('manage_jadwalkonsul');
         Route::get('/edit_jadwalkonsul/{id}', [AdminController::class, 'editJadwalKonsul'])->name('edit_jadwalkonsul');
         Route::put('/update_jadwalkonsul/{id}', [AdminController::class, 'updateJadwalKonsul'])->name('update_jadwalkonsul');
         Route::delete('/delete_jadwalkonsul/{id}', [AdminController::class, 'deleteJadwalkonsul'])->name('delete_jadwalkonsul');
         Route::post('/admin/jadwalkonsul/{id}/status', [JadwalKonsulController::class, 'updateStatusPembayaran'])->name('admin.update_status_pembayaran');


    });
});

Route::prefix('psikolog')->group(function () {
    Route::get('/login', [PsikologController::class, 'showLoginForm'])->name('psikolog.login');
    Route::post('/login', [PsikologController::class, 'login'])->name('psikolog.login.post');
    Route::post('/logout', [PsikologController::class, 'logout'])->name('psikolog.logout');
    Route::get('/register', [PsikologController::class, 'showRegisterForm'])->name('psikolog.register');
    Route::post('/register', [PsikologController::class, 'register'])->name('psikolog.register.post');
    Route::get('/forgot-password', [PsikologController::class, 'showForgotPasswordForm'])->name('psikolog.forgot_password');
    Route::post('/forgot-password', [PsikologController::class, 'sendResetLink'])->name('psikolog.forgot_password.post');

    Route::middleware('auth:psikolog')->group(function () {
        Route::get('/dashboard', [PsikologController::class, 'showDashboard'])->name('psikolog.dashboard');
        Route::get('/laporan', [PsikologController::class, 'showLaporanForm'])->name('psikolog.laporan.form');
        Route::post('/laporan', [PsikologController::class, 'submitLaporan'])->name('psikolog.laporan.submit');
        Route::get('/laporan-index', [PsikologController::class, 'showLaporanIndex'])->name('psikolog.laporan.index');
        Route::get('/laporan/edit/{laporanId}', [PsikologController::class, 'editLaporan'])->name('psikolog.laporan.edit');
        Route::put('/laporan/update/{laporanId}', [PsikologController::class, 'updateLaporan'])->name('psikolog.laporan.update');
        Route::post('/laporan/store/{jadwalId}', [PsikologController::class, 'storeLaporan'])->name('psikolog.laporan.store');
        Route::get('/laporan/form/{jadwalId}', [PsikologController::class, 'showLaporanForm'])->name('psikolog.laporan.forms');
        Route::get('/laporan/form/{jadwalId}', [PsikologController::class, 'showLaporanForm'])->name('psikolog.laporan.forms');

    });
});



Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/booking', [JadwalKonsulController::class, 'index'])->name('booking');
    Route::post('/booking/store', [JadwalKonsulController::class, 'store'])->name('booking.store');
    // Route untuk menyimpan jadwal konsultasi
Route::post('/jadwal/store', [JadwalKonsulController::class, 'store'])->name('jadwal.store');
Route::get('/nota/{id}', [JadwalKonsulController::class, 'showNota'])->name('nota.show');
Route::get('/riwayat', [AuthController::class, 'riwayatLaporanUser'])->name('user.riwayat');
Route::get('/download-laporan', [AuthController::class, 'downloadLaporan'])->name('download.laporan');



});

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    // Route untuk manage jadwal konsultasi
    Route::get('/manage_jadwalkonsul', [AdminController::class, 'manageJadwalKonsul'])->name('manage_jadwalkonsul');
    Route::get('/edit_jadwalkonsul/{id}', [AdminController::class, 'editJadwalKonsul'])->name('edit_jadwalkonsul');
    Route::put('/update_jadwalkonsul/{id}', [AdminController::class, 'updateJadwalKonsul'])->name('update_jadwalkonsul');
    Route::delete('/delete_jadwalkonsul/{id}', [AdminController::class, 'deleteJadwalkonsul'])->name('delete_jadwalkonsul');

    Route::get('/manage-laporan-psikolog', [AdminController::class, 'manageLaporanPsikolog'])->name('manage-laporan-psikolog');
    Route::get('/edit-laporan-psikolog/{id}', [AdminController::class, 'editLaporanPsikolog'])->name('edit_laporan_psikolog');
    Route::delete('/delete-laporan-psikolog/{id}', [AdminController::class, 'deleteLaporanPsikolog'])->name('delete_laporan_psikolog');
    Route::get('/laporan-konsul', [AdminController::class, 'laporanKonsul'])->name('laporan-konsul');
});

Route::get('/pembayaran/upload/{id}', [PembayaranController::class, 'formUpload'])->name('pembayaran.form');
Route::post('/pembayaran/upload/{id}', [PembayaranController::class, 'upload'])->name('pembayaran.upload');

Route::get('/check-booked-times', [JadwalKonsulController::class, 'checkBookedTimes']);
