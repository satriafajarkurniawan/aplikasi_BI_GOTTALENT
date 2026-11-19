<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Hash;

class JuriController extends Controller
{
    // --- BAGIAN TAMPILAN HALAMAN (MENUS) UNTUK JURI ---

    // 1. Dashboard Juri
    public function index()
    {
        return view('juri.dashboard');
    }

    // 2. Halaman Penilaian Peserta oleh Juri
    public function penilaianPeserta()
    {
        // Mengambil data pendaftaran peserta beserta relasi user dan lomba
        $pendaftarans = Pendaftaran::with(['user', 'lomba'])->get();
        return view('juri.penilaian_peserta', compact('pendaftarans'));
    }


    // --- BAGIAN PROSES DATA (PENILAIAN) ---

    // Menyimpan atau Update Nilai Peserta
    public function updateNilai(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Update nilai dan catatan peserta
        $pendaftaran->update([
            'nilai' => $request->nilai,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Nilai peserta berhasil disimpan!');
    }
}