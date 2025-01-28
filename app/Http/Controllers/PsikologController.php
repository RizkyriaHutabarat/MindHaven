<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\Psikolog;
use App\Models\LaporanPsikolog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class PsikologController extends Controller
{
    // Halaman registrasi psikolog
    public function showRegisterForm()
    {
        return view('psikolog.register');
    }

    // Proses registrasi psikolog
    public function register(Request $request)
{
    // Validasi input
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:psikologs',
        'password' => 'required|string|min:6|confirmed',  // Pastikan menggunakan 'confirmed' untuk validasi
        'spesialisasi' => 'nullable|string|max:255',
        'bio' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('uploads/psikolog', 'public');
    }

    // Simpan data psikolog ke database
    Psikolog::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'spesialisasi' => $request->spesialisasi,
        'bio' => $request->bio,
        'foto' => $fotoPath,
    ]);

    return redirect()->route('psikolog.login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    // Halaman login psikolog
    public function showLoginForm()
    {
        return view('psikolog.login');
    }

    // Proses login psikolog
    public function login(Request $request)
    {
        // Validasi input login
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        // Mencari psikolog berdasarkan email
        $psikolog = Psikolog::where('email', $validated['email'])->first();
    
        // Memeriksa apakah psikolog ditemukan dan passwordnya cocok
        if ($psikolog && Hash::check($validated['password'], $psikolog->password)) {
            // Login menggunakan guard psikolog
            Auth::guard('psikolog')->login($psikolog);
    
            // Redirect ke halaman dashboard atau halaman yang diinginkan
            return redirect()->route('psikolog.dashboard');
        }
    
        // Jika login gagal, kembalikan error
        return back()->withErrors(['email' => 'Email atau password salah']);
    }
    

    // Logout psikolog
    public function logout()
    {
        Auth::guard('psikolog')->logout();
        return redirect()->route('psikolog.login')->with('success', 'Logout berhasil.');
    }

    // Halaman lupa password
    public function showForgotPasswordForm()
    {
        return view('psikolog.forgot_password');
    }

    // Proses kirim link lupa password
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:psikolog,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Menampilkan halaman Dashboard Psikolog
    public function showDashboard()
    {
    
        return view('psikolog.dashboardpsikolog');

    }
    
    

    // Menampilkan form untuk membuat laporan
    public function showLaporanForm($jadwalId)
    {
        $jadwal = DB::table('tabel_jadwalkonsul')->find($jadwalId);
        
        if (!$jadwal) {
            return redirect()->route('psikolog.laporan.index')->withErrors('Jadwal konsultasi tidak ditemukan.');
        }
        
        return view('psikolog.laporan-form', compact('jadwal'));
    }
    

    // Proses penyimpanan laporan
    public function submitLaporan(Request $request, $jadwalId)
    {
        $request->validate([
            'deskripsi_laporan' => 'required|string',
            'hasil' => 'required|nullable|string',
            'status_laporan' => 'required|string|in:pending,selesai,ditolak',
        ]);
    
        $jadwal = DB::table('tabel_jadwalkonsul')->find($jadwalId);
    
        if (!$jadwal) {
            return redirect()->back()->withErrors('Jadwal konsultasi tidak ditemukan.');
        }
    
        LaporanPsikolog::create([
            'id_psikolog' => Auth::guard('psikolog')->id(),
            'id_users' => $jadwal->id_users,
            'id_paket' => $jadwal->id_pakets,
            'id_jadwalkonsul' => $jadwalId,
            'deskripsi_laporan' => $request->deskripsi_laporan,
            'hasil' => $request->hasil,
            'status_laporan' => $request->status_laporan,
        ]);
    
        return redirect()->route('psikolog.laporan.index')->with('success', 'Laporan berhasil disimpan.');
    }
    

    // Menampilkan daftar laporan hanya untuk psikolog yang sedang login
    public function showLaporanIndex(Request $request)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        // Jika ada psikolog yang dipilih melalui query parameter
        $idPsikolog = $request->query('id_psikolog', Auth::guard('psikolog')->id());
    
        // Mengambil data jadwal konsultasi untuk psikolog yang dipilih
        $jadwals = DB::table('tabel_jadwalkonsul')
            ->join('users', 'tabel_jadwalkonsul.id_users', '=', 'users.id_user')
            ->join('tabel_pakets', 'tabel_jadwalkonsul.id_pakets', '=', 'tabel_pakets.id')
            ->where('tabel_jadwalkonsul.id_psikologs', $idPsikolog)
            ->select('tabel_jadwalkonsul.*', 'users.nama as user_name', 'tabel_pakets.nama_paket')
            ->get();
    
        // Mengambil data laporan psikolog berdasarkan id_psikolog yang dipilih
        $laporans = DB::table('laporan_psikolog')
            ->where('id_psikolog', $idPsikolog)
            ->get();
    
        // Mengirimkan data jadwals dan laporans ke view
        return view('psikolog.laporan_index', compact('jadwals', 'laporans', 'idPsikolog'));
    }
    
    

    public function storeLaporan(Request $request, $id)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        $request->validate([
            'deskripsi_laporan' => 'required',
            'status_laporan' => 'required',
        ]);
    
        // Simpan laporan ke database
        LaporanPsikolog::create([
            'id_psikolog' => Auth::guard('psikolog')->id(),
            'id_users' => DB::table('tabel_jadwalkonsul')->where('id', $id)->value('id_users'),
            'id_paket' => DB::table('tabel_jadwalkonsul')->where('id', $id)->value('id_pakets'),
            'id_jadwalkonsul' => $id,
            'deskripsi_laporan' => $request->deskripsi_laporan,
            'hasil' => $request->hasil,
            'status_laporan' => $request->status_laporan,
        ]);
    
        return redirect()->route('psikolog.laporan.index')->with('success', 'Laporan berhasil disimpan.');
    }
    
    public function index()
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        $jadwals = DB::table('tabel_jadwalkonsul')
            ->join('users', 'tabel_jadwalkonsul.id_users', '=', 'users.id_user')
            ->join('tabel_pakets', 'tabel_jadwalkonsul.id_pakets', '=', 'tabel_pakets.id')
            ->select('tabel_jadwalkonsul.*', 'users.nama as user_name', 'tabel_pakets.nama_paket')
            ->where('tabel_jadwalkonsul.id_psikologs', Auth::guard('psikolog')->id())
            ->get();
    
        // Ambil data laporan psikolog berdasarkan id_psikolog
        $laporans = LaporanPsikolog::where('id_psikolog', Auth::guard('psikolog')->id())->get();
    
        // Kirim data jadwals dan laporans ke view
        return view('psikolog.laporan_index', compact('jadwals', 'laporans'));
    }

    public function editLaporan($laporanId)
    {
        // Mengambil laporan yang akan diedit
        $laporan = DB::table('laporan_psikolog')->where('id', $laporanId)->first();
    
        if (!$laporan) {
            return redirect()->route('psikolog.laporan.index')->with('error', 'Laporan tidak ditemukan.');
        }
    
        // Menampilkan form edit dengan data laporan
        return view('psikolog.laporan_edit', compact('laporan'));
    }
    
    public function updateLaporan(Request $request, $laporanId)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        // Validasi data
        $validated = $request->validate([
            'deskripsi_laporan' => 'required|string',
            'hasil' => 'required|nullable|string',
            'status_laporan' => 'required|string|in:pending,selesai,ditolak',
        ]);
    
        // Update laporan yang sudah ada
        DB::table('laporan_psikolog')
            ->where('id', $laporanId)
            ->update([
                'deskripsi_laporan' => $validated['deskripsi_laporan'],
                'hasil' => $validated['hasil'] ?? null,
                'status_laporan' => $validated['status_laporan'],
                'updated_at' => now(),
            ]);
    
        return redirect()->route('psikolog.laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }
    public function downloadFilteredLaporan(Request $request)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        $request->validate([
            'status_laporan' => 'required|in:pending,selesai,ditolak'
        ]);
    
        $laporans = LaporanPsikolog::where('status_laporan', $request->status_laporan)->get();
    
        if ($laporans->isEmpty()) {
            return back()->with('error', 'Tidak ada laporan dengan status tersebut.');
        }
    
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
    
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
    
        $filename = 'Laporan_' . $request->status_laporan . '_' . now()->format('Ymd_His') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);
    
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
    
    
}
