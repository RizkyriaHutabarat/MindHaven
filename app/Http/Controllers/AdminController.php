<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Psikolog;
use App\Models\Paket;
use App\Models\JadwalKonsul;
use App\Models\LaporanPsikolog;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class AdminController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard')->with('success', 'Login Successfully!');
        }
    
        return redirect()->back()->with('error', 'Email atau password salah!');
    }

    // Menampilkan halaman Dashboard Admin
    public function showDashboard()
    {
        // Fetch counts of pending and verified payments
        $pendingCount = JadwalKonsul::where('status_pembayaran', 'pending')->count();
        $verifiedCount = JadwalKonsul::where('status_pembayaran', 'verified')->count();
    
        // Return the data to the view
        return view('admin.dashboardadmin', compact('pendingCount', 'verifiedCount'));
    }

    // Logout Admin
    public function logout()
    {
        Auth::logout(); // Logout user
        return redirect()->route('admin.loginForm')->with('success', 'You have successfully logged out.');
    }

    // Menampilkan halaman Manage Users
    public function showManageUsers()
    {
        $users = User::all(); // Mengambil semua data pengguna
        return view('admin.manage_users', compact('users'));
    }

    // Metode untuk edit user
    public function editUser($id_user)
    {
        $user = User::findOrFail($id_user);
        return view('admin.edit_user', compact('user'));
    }

   // Metode untuk update user
    public function updateUser(Request $request, $id_user)
    {
    $user = User::findOrFail($id_user);

    // Validasi input
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id_user . ',id_user', // Perbaikan di sini
        'jenis_kelamin' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'nomor_telepon' => 'required|string',
    ]);

    // Update data pengguna
    $user->update([
        'nama' => $request->nama,
        'email' => $request->email,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tanggal_lahir' => $request->tanggal_lahir,
        'nomor_telepon' => $request->nomor_telepon,
    ]);

    return redirect()->route('admin.manage_users')->with('success', 'User updated successfully');
    }


    // Metode untuk delete user
    public function deleteUser($id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();
        return redirect()->route('admin.manage_users')->with('success', 'User deleted successfully');
    }

    public function managePsikologs()
    {
        // Mengambil semua data psikolog
        $psikologs = Psikolog::all();

        // Mengirim data psikolog ke view
        return view('admin.manage_psikologs', compact('psikologs'));
    }

        // Metode untuk edit psikolog
        public function editPsikolog($id)
        {
            $psikolog = Psikolog::findOrFail($id);
            return view('admin.edit_psikolog', compact('psikolog'));
        }
    
        // Metode untuk update psikolog
        public function updatePsikolog(Request $request, $id)
        {
            $psikolog = Psikolog::findOrFail($id);
    
            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:psikologs,email,' . $id . ',id',
                'spesialisasi' => 'nullable|string|max:255',
                'bio' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'required' => 'Form tidak boleh kosong. Harap isi semua kolom.',
            ]);
    
            // Update data psikolog
            $psikolog->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'spesialisasi' => $request->spesialisasi,
                'bio' => $request->bio,
            ]);
    
            // Handle photo upload
            if ($request->hasFile('foto')) {
                $photo = $request->file('foto');
                $photoPath = $photo->store('psikolog_photos', 'public');
                $psikolog->foto = $photoPath;
                $psikolog->save();
            }
    
            return redirect()->route('admin.manage_psikologs')->with('success', 'Psikolog updated successfully');
        }
    
        // Metode untuk delete psikolog
        public function deletePsikolog($id) 
        {
            $psikolog = Psikolog::findOrFail($id);
            // Delete photo if exists
            if ($psikolog->foto) {
                // Delete the file from the public storage
                Storage::disk('public')->delete($psikolog->foto);
            }
            $psikolog->delete();
            return redirect()->route('admin.manage_psikologs')->with('success', 'Psikolog deleted successfully');
        }

        // Manage Paket
        public function managePakets()
        {
            $pakets = Paket::all();
            return view('admin.manage_pakets', compact('pakets'));
        }

        public function createPaket()
        {
            return view('admin.create_paket');
        }

        public function store(Request $request)
        {
            $request->validate([
                'nama_paket' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for image
                'harga' => 'required|numeric|min:0', // Add validation for harga
            ]);
            
            $paket = new Paket;
            $paket->nama_paket = $request->nama_paket;
            $paket->deskripsi = $request->deskripsi;
            $paket->harga = $request->harga; // Save the harga field

            // Handle image upload
            if ($request->hasFile('gambar')) {
                $paket->gambar = $request->file('gambar')->store('paket_images', 'public');
            }

            $paket->save();

            return redirect()->route('admin.manage_pakets')->with('success', 'Paket added successfully!');
        }

        public function updatePaket(Request $request, $id)
        {
            // Validation for the fields
            $request->validate([
                'nama_paket' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for image
                'harga' => 'required|numeric|min:0', // Add validation for harga
            ]);

            // Find the paket by ID
            $paket = Paket::findOrFail($id);
            $paket->nama_paket = $request->nama_paket;
            $paket->deskripsi = $request->deskripsi;
            $paket->harga = $request->harga; // Update the harga field

            // Handle image upload
            if ($request->hasFile('gambar')) {
                // Delete old image if it exists
                if ($paket->gambar) {
                    Storage::disk('public')->delete($paket->gambar);
                }

                // Store the new image
                $paket->gambar = $request->file('gambar')->store('paket_images', 'public');
            }

            // Save the updated paket
            $paket->save();

            // Redirect with success message
            return redirect()->route('admin.manage_pakets')->with('success', 'Paket updated successfully!');
        }

        public function deletePaket($id)
        {
            $paket = Paket::findOrFail($id);

            // Hapus gambar dari storage jika ada
            if ($paket->gambar && Storage::exists('public/' . $paket->gambar)) {
                Storage::delete('public/' . $paket->gambar);
            }

            $paket->delete();

            return redirect()->route('admin.manage_pakets')->with('success', 'Paket deleted successfully');
        }

        // Manage Jadwal Konsultasi
    public function manageJadwalKonsul()
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        $jadwalKonsuls = JadwalKonsul::with(['psikolog', 'paket', 'user'])->get();
        return view('admin.manage_jadwalkonsul', compact('jadwalKonsuls'));
    }

    // Edit Jadwal Konsultasi
        // Controller: editJadwalKonsul
        public function editJadwalKonsul($id)
        {
            Carbon::setLocale('id'); // Mengatur locale ke Indonesia
            $jadwalKonsul = JadwalKonsul::with(['psikolog', 'paket', 'user'])->findOrFail($id);
            $pakets = Paket::all();
            $psikologs = Psikolog::all();
            return view('admin.edit_jadwalkonsul', compact('jadwalKonsul', 'pakets', 'psikologs'));
        }


    // Update Jadwal Konsultasi
    public function updateJadwalKonsul(Request $request, $id)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        $request->validate([
            'id_psikologs' => 'required|exists:psikologs,id',
            'id_pakets' => 'required|exists:tabel_pakets,id',
            'jam' => 'required|date_format:H:i',
            'hari' => 'required|string',
            'deskripsi' => 'required|string',
            'metodepembayaran' => 'required|string',
        ]);

        $jadwalKonsul = JadwalKonsul::findOrFail($id);
        $jadwalKonsul->id_psikologs = $request->id_psikologs;
        $jadwalKonsul->id_pakets = $request->id_pakets;
        $jadwalKonsul->jam = $request->jam;
        $jadwalKonsul->hari = $request->hari;
        $jadwalKonsul->deskripsi = $request->deskripsi;
        $jadwalKonsul->metodepembayaran = $request->metodepembayaran;
        $jadwalKonsul->save();

        return redirect()->route('admin.manage_jadwalkonsul')->with('success', 'Jadwal Konsultasi berhasil diperbarui!');
    }

    // Delete Jadwal Konsultasi
    public function deleteJadwalkonsul($id)
    {
        $jadwalKonsul = JadwalKonsul::findOrFail($id);
        $jadwalKonsul->delete();

        return redirect()->route('admin.manage_jadwalkonsul')->with('success', 'Jadwal Konsultasi berhasil dihapus!');
    }
    

    public function manageLaporanPsikolog()
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        // Mengambil semua data laporan psikolog
        $laporanPsikologs = LaporanPsikolog::with(['psikolog', 'user', 'paket', 'jadwalKonsul'])->get();
    
        // Mengirim data laporan psikolog ke view
        return view('admin.manage_laporan_psikolog', compact('laporanPsikologs'));
    }

    public function editLaporanPsikolog($id)
    {
        $laporan = LaporanPsikolog::findOrFail($id);
        return view('admin.edit_laporan_psikolog', compact('laporan'));
    }

    public function deleteLaporanPsikolog($id)
    {
        $laporan = LaporanPsikolog::findOrFail($id);
        $laporan->delete();
        return redirect()->route('admin.manage-laporan-psikolog')->with('success', 'Laporan Psikolog berhasil dihapus!');
    }

    public function downloadFilteredLaporan(Request $request)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
    
        // Validasi input request, hanya status laporan yang diperlukan
        $request->validate([
            'status_laporan' => 'required|in:pending,selesai,ditolak',
        ]);
    
        // Ambil laporan sesuai dengan filter status
        $laporans = LaporanPsikolog::query(); // Memulai query
    
        // Filter berdasarkan status laporan
        $laporans->where('status_laporan', $request->status_laporan);
    
        $laporans = $laporans->get();
    
        // Jika tidak ada laporan yang ditemukan
        if ($laporans->isEmpty()) {
            return back()->with('error', 'Tidak ada laporan dengan filter status tersebut.');
        }
    
        // Membuat file Word baru
        $phpWord = new PhpWord();
    
        // Menambahkan total laporan di bagian atas dokumen
        $section = $phpWord->addSection();
        $section->addText('Total Laporan: ' . $laporans->count(), ['bold' => true, 'size' => 14, 'color' => '1F4E79'], ['alignment' => 'center']);
        $section->addTextBreak(1);
    
        // Menambahkan laporan-laporan ke dalam dokumen
        foreach ($laporans as $laporan) {
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
    
            // Judul Laporan
            $section->addText('Laporan Psikolog', ['bold' => true, 'size' => 18, 'color' => '1F4E79'], ['alignment' => 'center']);
            $section->addTextBreak(1);
    
            // Informasi Psikolog
            $section->addText('Nama Psikolog: ' . $laporan->psikolog->nama, ['bold' => true, 'size' => 14, 'color' => '4F81BD']);
            $section->addText('Tanggal Konsultasi: ' . \Carbon\Carbon::parse($laporan->jadwalKonsul->tanggal)->translatedFormat('l, d F Y'), ['size' => 12]);
            $section->addText('Jam Konsultasi: ' . ($laporan->jadwalKonsul->jam ?? 'N/A'), ['size' => 12]);
            $section->addText('Paket: ' . $laporan->paket->nama_paket, ['size' => 12]);
            $section->addText('Harga: ' . ($laporan->paket->harga ?? 'N/A'), ['size' => 12]);
    
            // Hasil Konsultasi
            $section->addText('Hasil: ', ['bold' => true, 'size' => 12]);
            $section->addText(substr($laporan->hasil, 0, 1000) . '...', ['italic' => true, 'size' => 12]);
    
            // Rekomendasi
            $section->addText('Rekomendasi: ', ['bold' => true, 'size' => 12]);
            $section->addText($laporan->deskripsi_laporan, ['size' => 12]);
    
            // Status Laporan
            $statusColor = $laporan->status_laporan === 'pending' ? 'FF0000' : '28A745';
            $statusText = ucfirst($laporan->status_laporan);
            $section->addText('Status: ' . $statusText, ['bold' => true, 'size' => 12, 'color' => $statusColor]);
    
            $section->addText('-----------------------------------------------------------', ['bold' => true, 'size' => 12]);
            $section->addTextBreak(1);
        }
    
        // Menyimpan dokumen sementara
        $filename = 'Laporan_' . $request->status_laporan . '_' . now()->format('Ymd_His') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);
    
        // Mengunduh file
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
    
}
