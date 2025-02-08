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
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;



class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    // public function login(Request $request)
    // {
    //     // Ambil input email dan password
    //     $credentials = $request->only('email', 'password');

    //     // Coba autentikasi
    //     if (Auth::attempt($credentials)) {
    //         // Jika login berhasil, set session 'success'
    //         return redirect()->intended('/dashboard')->with('success', 'Login berhasil. Selamat datang!');
    //     }

    //     // Jika login gagal, set session 'error'
    //     return back()->with('error', 'Email atau password salah.');
    // }


    public function login(Request $request)
    {
        try {
            Log::info('Login Attempt', [
                'email' => $request->email,
                'time' => now(),
                'session_id' => session()->getId(),
            ]);

            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            // Debug guard states sebelum login
            Log::info('Guard States Before Login', [
                'admin_check' => Auth::guard('admin')->check(),
                'psikolog_check' => Auth::guard('psikolog')->check(),
                'web_check' => Auth::guard('web')->check(),
            ]);

            // Try admin login
            if (Auth::guard('admin')->attempt($credentials)) {
                Log::info('Admin Login Success', [
                    'user' => Auth::guard('admin')->user(),
                    'session_id' => session()->getId()
                ]);


                $token = $request->user('admin')->createToken('admin_token')->plainTextToken;

                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'))
                    ->with('admin_token', $token);
            }

            // Debug Psikolog attempt
            $psikolog = Psikolog::where('email', $credentials['email'])->first();
            Log::info('Psikolog Check', [
                'found' => $psikolog ? true : false,
                'password_check' => $psikolog ? Hash::check($credentials['password'], $psikolog->password) : false
            ]);

            if ($psikolog && Hash::check($credentials['password'], $psikolog->password)) {
                Auth::guard('psikolog')->login($psikolog);
                Log::info('Psikolog Login Success', [
                    'user' => $psikolog,
                    'session_id' => session()->getId()
                ]);

                $token = $request->user('psikolog')->createToken('psikolog_token')->plainTextToken;

                $request->session()->regenerate();
                return redirect()->route('psikolog.dashboard')
                    ->with('psikolog_token', $token);
            }

            // Try regular user login
            if (Auth::guard('web')->attempt($credentials)) {
                Log::info('User Login Success', [
                    'user' => Auth::guard('web')->user(),
                    'session_id' => session()->getId()
                ]);

                $token = $request->user()->createToken('user_token')->plainTextToken;

                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'))
                    ->with('user_token', $token);
            }

            Log::error('Login Failed', [
                'email' => $request->email,
                'time' => now()
            ]);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        } catch (\Exception $e) {
            Log::error('Login Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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

        // create token


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

    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    // }

    public function logout(Request $request)
    {
       // Hapus semua session dan regenerate
    $request->session()->flush();

    // Logout dari semua guard
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    Auth::guard('psikolog')->logout();

    // Invalidate session dan regenerate CSRF token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Clear cookie auth
    return redirect('/login')->withCookies([
        cookie()->forget('laravel_session'),
        cookie()->forget('XSRF-TOKEN')
    ]);
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

    public function downloadLaporan(Request $request)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        // Mendapatkan ID user yang sedang login
        $userId = Auth::id();

        // Ambil semua data riwayat tanpa filter status
        $riwayat = LaporanPsikolog::with(['psikolog', 'paket', 'jadwalKonsul'])
                                    ->where('id_users', $userId)
                                    ->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Styling for title
        $titleStyle = ['bold' => true, 'size' => 18, 'color' => '1F4E79'];
        $section->addText('Laporan Riwayat Konsultasi', $titleStyle, ['alignment' => 'center']);
        $section->addTextBreak(1);

        if ($riwayat->isEmpty()) {
            $section->addText('Belum ada riwayat konsultasi.', ['italic' => true, 'size' => 14, 'color' => 'FF0000']);
        } else {
            foreach ($riwayat as $item) {
                // Add a section break for each consultation entry
                $section->addText('-----------------------------------------------------------', ['bold' => true, 'size' => 12]);
                $section->addTextBreak(1);

                // Psikolog Name with Custom Style
                $section->addText('Psikolog: ' . ($item->psikolog->nama ?? 'N/A'), ['bold' => true, 'size' => 14, 'color' => '4F81BD']);
                $section->addText('Tanggal Konsultasi: ' . \Carbon\Carbon::parse($item->jadwalKonsul->tanggal)->translatedFormat('l, d F Y '), ['size' => 12]);
                $section->addText('Jam Konsultasi: ' . $item->jadwalKonsul->jam ?? 'N/A', [ 'size' => 12]);
                $section->addText('Paket: ' . ($item->paket->nama_paket ?? 'N/A'), ['size' => 12]);

                // Descriptions with more distinct formatting
                $section->addText('Deskripsi Klien: ', ['bold' => true, 'size' => 12]);
                $section->addText($item->jadwalKonsul->deskripsi ?? 'N/A', ['italic' => true, 'size' => 12]);

                $section->addText('Deskripsi Laporan: ', ['bold' => true, 'size' => 12]);
                $section->addText($item->deskripsi_laporan, ['size' => 12]);

                // Shortened result with ellipsis
                $section->addText('Hasil: ', ['bold' => true, 'size' => 12]);
                $section->addText(substr($item->hasil, 0, 1000) . '...', ['italic' => true, 'size' => 12]);

                // Status with Custom Color
                $status = $item->status_laporan === 'pending' ? 'Pending' : 'Selesai';
                $section->addText('Status: ' . $status, ['bold' => true, 'size' => 12, 'color' => $item->status_laporan === 'pending' ? 'FF0000' : '28A745']);

                $section->addText('-----------------------------------------------------------', ['bold' => true, 'size' => 12]);
                $section->addTextBreak(1);
            }
        }

        // File name with timestamp
        $filename = 'Laporan_Riwayat_Konsultasi_' . now()->format('Ymd_His') . '.docx';

        // Save the file temporarily
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        // Return the file as a download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

}
