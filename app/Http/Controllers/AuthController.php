<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Paket;
use App\Models\Pembelian;
use App\Models\Psikolog;
use App\Models\LaporanPsikolog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        // Cek autentikasi untuk admin
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil sebagai Admin!');
        }
    
        // Cek autentikasi untuk psikolog
        if (Auth::guard('psikolog')->attempt($credentials)) {
            return redirect()->route('psikolog.dashboard')->with('success', 'Login berhasil sebagai Psikolog!');
        }
    
        // Cek autentikasi untuk user biasa
        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('/dashboard')->with('success', 'Login berhasil. Selamat datang!');
        }
    
        // Jika login gagal untuk semua roles
        return back()->with('error', 'Email atau password salah.');
    }
    

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        // Buat user baru
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telepon' => $request->nomor_telepon,
            'tipe_user' => 'klien',
        ]);

        // Redirect ke login dengan session 'success'
        return redirect('/login')->with('success', 'Pendaftaran berhasil. Silakan login.');
    }


    public function showDashboard()
    {
        $user = Auth::user();
        $pakets = Paket::all();
        $psikologs = Psikolog::all(); // Ambil data psikolog

        if ($user->tipe_user === 'admin') {
            // Jika admin
            $users = User::all(); // Ambil semua data pengguna dari tabel `users`
            return view('admin.dashboardadmin', compact('users'));
        } elseif ($user->tipe_user === 'klien') {
            // Jika klien
            return view('dashboard', compact('pakets', 'psikologs'));
        }

        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }


    public function riwayatLaporanUser()
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
    
        // Mendapatkan ID user yang sedang login
        $userId = Auth::id();
    
        // Mengambil data laporan berdasarkan ID user yang login
        $riwayat = LaporanPsikolog::with(['psikolog', 'paket', 'jadwalKonsul'])
            ->where('id_users', $userId) // Gunakan id_users, sesuai dengan nama kolom di database
            ->get();
    
        // Mengirim data riwayat ke view
        return view('riwayat', compact('riwayat'));
    }

    // public function downloadLaporan(Request $request)
    // {
    //     Carbon::setLocale('id'); // Mengatur locale ke Indonesia
    //     // Mendapatkan ID user yang sedang login
    //     $userId = Auth::id();
    
    //     // Ambil semua data riwayat tanpa filter status
    //     $riwayat = LaporanPsikolog::with(['psikolog', 'paket', 'jadwalKonsul'])
    //                                 ->where('id_users', $userId)
    //                                 ->get();
    
    //     $phpWord = new PhpWord();
    //     $section = $phpWord->addSection();
    
    //     // Styling for title
    //     $titleStyle = ['bold' => true, 'size' => 18, 'color' => '1F4E79'];
    //     $section->addText('Laporan Riwayat Konsultasi', $titleStyle, ['alignment' => 'center']);
    //     $section->addTextBreak(1);
    
    //     if ($riwayat->isEmpty()) {
    //         $section->addText('Belum ada riwayat konsultasi.', ['italic' => true, 'size' => 14, 'color' => 'FF0000']);
    //     } else {
    //         foreach ($riwayat as $item) {
    //             // Add a section break for each consultation entry
    //             $section->addText('-----------------------------------------------------------', ['bold' => true, 'size' => 12]);
    //             $section->addTextBreak(1);
    
    //             // Psikolog Name with Custom Style
    //             $section->addText('Psikolog: ' . ($item->psikolog->nama ?? 'N/A'), ['bold' => true, 'size' => 14, 'color' => '4F81BD']);
    //             $section->addText('Tanggal Konsultasi: ' . \Carbon\Carbon::parse($item->jadwalKonsul->tanggal)->translatedFormat('l, d F Y '), ['size' => 12]);
    //             $section->addText('Jam Konsultasi: ' . $item->jadwalKonsul->jam ?? 'N/A', [ 'size' => 12]);
    //             $section->addText('Paket: ' . ($item->paket->nama_paket ?? 'N/A'), ['size' => 12]);
    
    //             // Descriptions with more distinct formatting
    //             $section->addText('Deskripsi Klien: ', ['bold' => true, 'size' => 12]);
    //             $section->addText($item->jadwalKonsul->deskripsi ?? 'N/A', ['italic' => true, 'size' => 12]);
    
    //             $section->addText('Deskripsi Laporan: ', ['bold' => true, 'size' => 12]);
    //             $section->addText($item->deskripsi_laporan, ['size' => 12]);
    
    //             // Shortened result with ellipsis
    //             $section->addText('Hasil: ', ['bold' => true, 'size' => 12]);
    //             $section->addText(substr($item->hasil, 0, 1000) . '...', ['italic' => true, 'size' => 12]);
    
    //             // Status with Custom Color
    //             $status = $item->status_laporan === 'pending' ? 'Pending' : 'Selesai';
    //             $section->addText('Status: ' . $status, ['bold' => true, 'size' => 12, 'color' => $item->status_laporan === 'pending' ? 'FF0000' : '28A745']);
    
    //             $section->addText('-----------------------------------------------------------', ['bold' => true, 'size' => 12]);
    //             $section->addTextBreak(1);
    //         }
    //     }
    
    //     // File name with timestamp
    //     $filename = 'Laporan_Riwayat_Konsultasi_' . now()->format('Ymd_His') . '.docx';
    
    //     // Save the file temporarily
    //     $tempFile = tempnam(sys_get_temp_dir(), $filename);
    //     $writer = IOFactory::createWriter($phpWord, 'Word2007');
    //     $writer->save($tempFile);
    
    //     // Return the file as a download
    //     return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    // }    

    public function downloadAllLaporan()
    {
        Carbon::setLocale('id');
        $userId = Auth::id();
        $laporan = LaporanPsikolog::where('id_users', $userId)->get();
    
        if ($laporan->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada laporan yang tersedia untuk diunduh.');
        }
    
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
    
        foreach ($laporan as $data) {
            $section = $phpWord->addSection();
    
            // Menambahkan logo di bagian atas dokumen
            $logoPath = public_path('assets/images/logomindhaven1.png');
            if (file_exists($logoPath)) {
                $section->addImage($logoPath, [
                    'width' => 250,
                    'height' => 150,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'wrappingStyle' => 'inline',
                ]);
                $section->addTextBreak(1);
            }
    
            // Add title
            $section->addText('Laporan Hasil Konsultasi', ['bold' => true, 'size' => 18, 'color' => '1F4E79'], ['alignment' => 'center']);
            $section->addTextBreak(1);
    
            // Psikolog Name
            $section->addText('Nama Psikolog: ' . $data->psikolog->nama, ['bold' => true, 'size' => 14, 'color' => '4F81BD']);
            $section->addText('Tanggal Konsultasi: ' . \Carbon\Carbon::parse($data->jadwalKonsul->tanggal)->translatedFormat('l, d F Y'), ['size' => 12]);
            $section->addText('Jam Konsultasi: ' . $data->jadwalKonsul->jam ?? 'N/A', [ 'size' => 12]);
            $section->addText('Paket: ' . $data->paket->nama_paket, ['size' => 12]);
            $section->addText('Harga: ' . ($data->paket->harga ?? 'N/A'), ['size' => 12]);
    
            // Result
            $section->addText('Hasil: ', ['bold' => true, 'size' => 12]);
            $section->addText(substr($data->hasil, 0, 1000) . '...', ['italic' => true, 'size' => 12]);
    
            // Descriptions
            $section->addText('Rekomendasi : ', ['bold' => true, 'size' => 12]);
            $section->addText($data->deskripsi_laporan, ['size' => 12]);
    
            // Status with Custom Color
            $status = $data->status_laporan === 'pending' ? 'Pending' : 'Selesai';
            $section->addText('Status: ' . $status, ['bold' => true, 'size' => 12, 'color' => $data->status_laporan === 'pending' ? 'FF0000' : '28A745']);
    
            $section->addText('-----------------------------------------------------------', ['bold' => true, 'size' => 12]);
            $section->addTextBreak(1);
        }
    
        // File name with timestamp
        $fileName = 'Laporan_Hasil_Konsultasi_MindHaven ' . now()->format('Ymd_His') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);
    
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    
    public function downloadLaporanById($id)
    {
        Carbon::setLocale('id');
        $userId = Auth::id();
        $data = LaporanPsikolog::with(['psikolog', 'paket', 'jadwalKonsul'])
            ->where('id', $id)
            ->where('id_users', $userId)
            ->first();
    
        if (!$data) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan.');
        }
    
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
    
        // Menambahkan logo di bagian atas dokumen
        $logoPath = public_path('assets/images/logomindhaven1.png');
        if (file_exists($logoPath)) {
            $section->addImage($logoPath, [
                'width' => 250,
                'height' => 150,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'wrappingStyle' => 'inline',
            ]);
            $section->addTextBreak(1);
        }
    
        // Add title
        $section->addText('Laporan Hasil Konsultasi', ['bold' => true, 'size' => 18, 'color' => '1F4E79'], ['alignment' => 'center']);
        $section->addTextBreak(1);
    
        // Psikolog Name
        $section->addText('Nama Psikolog: ' . $data->psikolog->nama, ['bold' => true, 'size' => 14, 'color' => '4F81BD']);
        $section->addText('Tanggal Konsultasi: ' . \Carbon\Carbon::parse($data->jadwalKonsul->tanggal)->translatedFormat('l, d F Y'), ['size' => 12]);
        $section->addText('Jam Konsultasi: ' . $data->jadwalKonsul->jam ?? 'N/A', [ 'size' => 12]);
        $section->addText('Paket: ' . $data->paket->nama_paket, ['size' => 12]);
        $section->addText('Harga: ' . ($data->paket->harga ?? 'N/A'), ['size' => 12]);
    
        // Result
        $section->addText('Hasil: ', ['bold' => true, 'size' => 12]);
        $section->addText(substr($data->hasil, 0, 1000) . '...', ['italic' => true, 'size' => 12]);
    
        // Descriptions
        $section->addText('Rekomendasi: ', ['bold' => true, 'size' => 12]);
        $section->addText($data->deskripsi_laporan, ['size' => 12]);
    
        // Status with Custom Color
        $status = $data->status_laporan === 'pending' ? 'Pending' : 'Selesai';
        $section->addText('Status: ' . $status, ['bold' => true, 'size' => 12, 'color' => $data->status_laporan === 'pending' ? 'FF0000' : '28A745']);
    
        $fileName = 'Laporan_Konsultasi_Mindhaven ' . $id . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);
    
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }    
    

}
