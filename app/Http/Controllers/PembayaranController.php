<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\JadwalKonsul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// class PembayaranController extends Controller
// {
//     public function showPaymentForm($id)
//     {
//         // Ambil data jadwal konsultasi berdasarkan ID
//         $jadwalkonsul = JadwalKonsul::findOrFail($id);

//         // Kirimkan data ke view
//         return view('upload_payment', compact('jadwalkonsul'));
//     }

//     public function store(Request $request, $id)
//     {
//         // Validasi bukti pembayaran
//         $request->validate([
//             'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
//         ]);

//         // Ambil jadwal konsultasi berdasarkan ID
//         $jadwalkonsul = JadwalKonsul::findOrFail($id);

//         // Ambil nilai yang dibutuhkan dari jadwal konsultasi
//         $id_users = $jadwalkonsul->id_users;
//         $id_psikolog = $jadwalkonsul->id_psikolog;  // Perbaiki akses ke kolom id_psikolog
//         $id_paket = $jadwalkonsul->id_paket;

//         // Pastikan ID yang diperlukan tidak kosong
//         if (!$id_users || !$id_psikolog || !$id_paket) {
//             return back()->with('error', 'Data terkait jadwal tidak lengkap.');
//         }

//         // Simpan bukti pembayaran
//         $filePath = $request->file('bukti_pembayaran')->store('uploads/buktipembayaran', 'public');

//         // Periksa apakah file berhasil diupload
//         if (!$filePath) {
//             return back()->with('error', 'File gagal diupload.');
//         }

//         // Gunakan transaksi untuk memastikan semua proses berhasil
//         DB::beginTransaction();

//         try {
//             // Simpan data pembayaran ke tabel pembelian
//             Pembelian::create([
//                 'id_users' => $id_users,               // ID pengguna
//                 'id_jadwalkonsul' => $id,              // ID jadwal konsultasi
//                 'id_psikolog' => $id_psikolog,        // ID psikolog (perbaikan di sini)
//                 'id_paket' => $id_paket,              // ID paket
//                 'bukti_pembayaran' => $filePath,       // File bukti pembayaran
//                 'status_pembayaran' => 'pending',      // Status pembayaran
//                 'created_at' => now(),                 // Tanggal pembelian
//                 'updated_at' => now(),                 // Tanggal pembelian
//             ]);

//             // Commit transaksi jika tidak ada kesalahan
//             DB::commit();

//             // Redirect ke halaman nota setelah pembayaran berhasil diupload
//             return redirect()->route('nota.show', $id)->with('success', 'Pembayaran berhasil diupload. Menunggu verifikasi!');
//         } catch (\Exception $e) {
//             // Rollback transaksi jika terjadi kesalahan
//             DB::rollBack();
//             Log::error('Error uploading payment: ' . $e->getMessage());
//             return back()->with('error', 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage());
//         }
//     }
    
// }
class PembayaranController extends Controller
{
    public function formUpload($id)
    {
        $pembelian = Pembelian::where('id_jadwalkonsul', $id)->first();
        return view('pembayaran.upload', compact('pembelian'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $pembelian = Pembelian::where('id_jadwalkonsul', $id)->first();
        if ($pembelian) {
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/bukti_pembayaran'), $fileName);

                $pembelian->bukti_pembayaran = $fileName;
                $pembelian->status_pembayaran = 'pending';
                $pembelian->save();

                return redirect()->route('home')->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi.');
            }
        }

        return back()->withErrors('Gagal mengunggah bukti pembayaran.');
    }
}