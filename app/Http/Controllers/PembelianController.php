<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class PembelianController extends Controller
{
    // Menyimpan data pembelian dan mengarahkan ke halaman nota
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_jadwal_konsul' => 'required|exists:jadwal_konsul,id',
            'id_psikolog' => 'required|exists:psikolog,id',
            'id_paket' => 'required|exists:paket,id',
        ]);

        // Simpan data pembelian
        $pembelian = Pembelian::create([
            'id_user' => $validated['id_user'],
            'id_jadwal_konsul' => $validated['id_jadwal_konsul'],
            'id_psikolog' => $validated['id_psikolog'],
            'id_paket' => $validated['id_paket'],
        ]);

        // Redirect ke halaman nota
        return redirect()->route('nota', ['id' => $pembelian->id]);
    }

    // Menampilkan halaman nota berdasarkan ID pembelian
    public function showNota($id)
    {
        // Pastikan user memiliki role 'user'
        if (Auth::user()->role !== 'user') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Logika untuk mengambil data nota berdasarkan ID
        // Misalnya:
        $nota = Pembelian::find($id);

        if (!$nota) {
            return redirect()->route('dashboard')->with('error', 'Nota tidak ditemukan.');
        }

        return view('nota', compact('nota'));
    }
}
