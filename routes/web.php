<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Untuk mengecek user yang login
use App\Http\Controllers\AdminController; // Memanggil Controller Admin
use App\Http\Controllers\SiswaController; // Memanggil Controller Siswa
use App\Http\Controllers\JuriController;  // Memanggil Controller Juri
use App\Http\Controllers\ProfileController;

// 1. Rute Halaman Depan (Welcome Page)
Route::get('/', function () {
    return view('welcome');
});

// 2. Rute "Penyortir" Pintar (Otomatis arahkan sesuai Role sesaat setelah Login)
Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'juri') {
        return redirect()->route('juri.dashboard');
    }
    
    return redirect()->route('siswa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. JALUR KHUSUS ADMIN (Dijaga oleh Middleware Role:admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Halaman Utama Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // --- HALAMAN MENU ADMIN ---
    Route::get('/admin/kelola-lomba', [AdminController::class, 'kelolaLomba'])->name('admin.lomba');
    Route::get('/admin/kelola-siswa', [AdminController::class, 'kelolaSiswa'])->name('admin.siswa');
    Route::get('/admin/kelola-juri', [AdminController::class, 'kelolaJuri'])->name('admin.juri'); // Tambahan Kelola Juri
    Route::get('/admin/kelola-status', [AdminController::class, 'kelolaStatus'])->name('admin.status');

    // --- PROSES DATA LOMBA & STATUS ---
    Route::post('/admin/lomba', [AdminController::class, 'storeLomba'])->name('admin.lomba.store');
    Route::get('/admin/lomba/{id}/edit', [AdminController::class, 'editLomba'])->name('admin.lomba.edit');
    Route::put('/admin/lomba/{id}', [AdminController::class, 'updateLomba'])->name('admin.lomba.update');
    Route::delete('/admin/lomba/{id}', [AdminController::class, 'destroyLomba'])->name('admin.lomba.destroy');
    Route::post('/admin/pendaftaran/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.status.update');
    
    // --- PROSES DATA SISWA ---
    Route::post('/admin/siswa', [AdminController::class, 'storeSiswa'])->name('admin.siswa.store');
    Route::get('/admin/siswa/{id}/edit', [AdminController::class, 'editSiswa'])->name('admin.siswa.edit');
    Route::put('/admin/siswa/{id}', [AdminController::class, 'updateSiswa'])->name('admin.siswa.update');
    Route::delete('/admin/siswa/{id}', [AdminController::class, 'destroySiswa'])->name('admin.siswa.destroy');

    // --- PROSES DATA JURI (Tambahan) ---
    Route::post('/admin/juri', [AdminController::class, 'storeJuri'])->name('admin.juri.store');
    Route::get('/admin/juri/{id}/edit', [AdminController::class, 'editJuri'])->name('admin.juri.edit');
    Route::put('/admin/juri/{id}', [AdminController::class, 'updateJuri'])->name('admin.juri.update');
    Route::delete('/admin/juri/{id}', [AdminController::class, 'destroyJuri'])->name('admin.juri.destroy');
});

// 4. JALUR KHUSUS JURI (Dijaga oleh Middleware Role:juri)
Route::middleware(['auth', 'role:juri'])->group(function () {
    // Halaman Dashboard Juri
    Route::get('/juri/dashboard', [JuriController::class, 'index'])->name('juri.dashboard');
    
    // Halaman Penilaian Peserta
    Route::get('/juri/penilaian', [JuriController::class, 'penilaianPeserta'])->name('juri.penilaian');
    
    // Proses Simpan/Update Nilai Peserta
    Route::post('/juri/penilaian/{id}', [JuriController::class, 'updateNilai'])->name('juri.penilaian.update');
});

// 5. JALUR KHUSUS SISWA (Dijaga oleh Middleware Role:siswa)
Route::middleware(['auth', 'role:siswa'])->group(function () {
    // Halaman Dashboard Siswa
    Route::get('/siswa/dashboard', [SiswaController::class, 'index'])->name('siswa.dashboard');
    
    // Proses Daftar Lomba
    Route::post('/siswa/daftar', [SiswaController::class, 'daftarLomba'])->name('siswa.daftar');
});

// 6. Rute Profile Bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Bawaan Breeze untuk rute Login, Register, Logout, dll (JANGAN DIHAPUS)
require __DIR__.'/auth.php';