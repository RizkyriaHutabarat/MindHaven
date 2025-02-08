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

}
