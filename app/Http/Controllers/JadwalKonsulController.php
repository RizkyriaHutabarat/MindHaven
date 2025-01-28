<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalKonsul;
use App\Models\Paket;
use App\Models\Psikolog;
use Carbon\Carbon;

class JadwalKonsulController extends Controller
{
    public function index()
    {
        $pakets = Paket::all();
        $psikologs = Psikolog::all();
        $bookedTimes = [];
        $user = Auth::user();
        
        // Gunakan psikolog yang dipilih oleh pengguna atau default dari database
        $selectedPsikolog = request()->get('psikolog_id', $user->selected_psikolog);

    
        // Ambil waktu yang sudah dipesan untuk psikolog dan tanggal tertentu
        if (request()->has('tanggal')) {
            $bookedTimes = JadwalKonsul::where('id_psikologs', $selectedPsikolog)
                ->where('tanggal', request('tanggal'))
                ->pluck('jam')
                ->toArray();
        }
    
        return view('booking', compact('pakets', 'psikologs', 'bookedTimes', 'selectedPsikolog'));
    }
    

    public function store(Request $request)
    {
        Carbon::setLocale('id');
        
        // Validate the input data
        $request->validate([
            'id_psikologs' => 'required|exists:psikologs,id',
            'id_pakets' => 'required|exists:tabel_pakets,id',
            'jam' => 'required|date_format:H:i',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'metodepembayaran' => 'required|string',
            'jenis_konsultasi' => 'required|string|in:online,offline', // Validate the consultation type
            'link_meet' => 'nullable|string|url', // Validate the meet link
        ]);
        
        // Ensure the user is logged in
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
    
        // Get the logged-in user's ID
        $id_user = Auth::user()->id_user;
    
        // Check if the selected time slot is already booked
        $existingBooking = JadwalKonsul::where('id_psikologs', $request->id_psikologs)
            ->where('tanggal', $request->tanggal)
            ->where('jam', $request->jam)
            ->exists();
    
        if ($existingBooking) {
            return redirect()->back()->with('error', 'Jam ini sudah dipesan, silakan pilih waktu lain.');
        }
    
        // Determine the day of the week from the selected date
        $tanggal = Carbon::parse($request->tanggal);
        $hari = $tanggal->translatedFormat('l'); // Get the day in local language, like Senin, Selasa, etc.
    
        // If the consultation type is offline, set link_meet to null
        $linkMeet = ($request->jenis_konsultasi === 'offline') ? null : $request->link_meet;
    
        // Store the new booking
        $jadwal = JadwalKonsul::create([
            'id_psikologs' => $request->id_psikologs,
            'id_pakets' => $request->id_pakets,
            'id_users' => $id_user,
            'jam' => $request->jam,
            'tanggal' => $request->tanggal,
            'hari' => $hari,
            'deskripsi' => $request->deskripsi,
            'metodepembayaran' => $request->metodepembayaran,
            'bukti_pembayaran' => $request->bukti_pembayaran,
            'jenis_konsultasi' => $request->jenis_konsultasi, // Save consultation type
            'link_meet' => $linkMeet, // Save null if offline
        ]);
    
        // Redirect to the booking details page with success message
        return redirect()->route('nota.show', $jadwal->id)->with('success', 'Booking berhasil disimpan!');
    }
    
    
    public function showNota($id)
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        // Ambil data booking beserta relasi user, psikolog, dan paket
        $booking = JadwalKonsul::with(['psikolog', 'paket', 'user'])->findOrFail($id);
    
        // Kirim data ke view nota
        return view('nota', compact('booking'));
    }

    public function showUserJadwal()
    {
        Carbon::setLocale('id'); // Mengatur locale ke Indonesia
        // Pastikan user login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
    
        // Ambil ID user
        $id_user = Auth::user()->id_user;
    
        // Ambil semua jadwal yang terkait dengan user tersebut
        $jadwals = JadwalKonsul::where('id_users', $id_user)->get();
    
        // Kirim data jadwal ke view
        return view('user-jadwal', compact('jadwals'));
    }

    // Method untuk menampilkan halaman update jadwal
    public function showUpdateForm($id)
    {
        $jadwal = JadwalKonsul::findOrFail($id);
        return view('update-jadwal', compact('jadwal'));
    }

    // Method untuk melakukan update jadwal
// Method untuk melakukan update jadwal
public function update(Request $request, $id)
{
    Carbon::setLocale('id'); // Mengatur locale ke Indonesia
    $jadwal = JadwalKonsul::findOrFail($id);

    // Validasi input
    $request->validate([
        'jam' => 'required|date_format:H:i',
        'tanggal' => 'required|date',    
        'hari' => 'required|string',
        'jenis_konsultasi' => 'required|string|in:online,offline', // Validasi jenis konsultasi
    ]);

    // Check if the selected time slot (jam, tanggal) for the same psychologist is already booked by another user
    $existingBooking = JadwalKonsul::where('id_psikologs', $jadwal->id_psikologs)
        ->where('tanggal', $request->tanggal)
        ->where('jam', $request->jam)
        ->where('id', '!=', $id) // Exclude the current booking (the one being updated)
        ->exists();

    if ($existingBooking) {
        return redirect()->back()->with('error', 'Jam ini sudah dipesan, silakan pilih waktu lain.');
    }

    // Check if jenis_konsultasi is changing from 'online' to 'offline'
    if ($jadwal->jenis_konsultasi == 'online' && $request->jenis_konsultasi == 'offline') {
        // Remove the Google Meet link if switching to offline
        $jadwal->link_meet = null;
    }

    // Update data jadwal, including other fields
    $jadwal->update($request->only(['jam', 'hari', 'tanggal', 'jenis_konsultasi', 'link_meet']));

    // Redirect ke halaman jadwal
    return redirect()->route('user.jadwal')->with('success', 'Jadwal berhasil diperbarui!');
}

   
   // Method untuk update status pembayaran
    public function updateStatusPembayaran($id)
    {
        // Temukan jadwal konsultasi berdasarkan ID
        $jadwal = JadwalKonsul::findOrFail($id);

        // Toggle status pembayaran (verified / pending)
        $jadwal->status_pembayaran = $jadwal->status_pembayaran == 'verified' ? 'pending' : 'verified';

        // Simpan perubahan
        $jadwal->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.manage_jadwalkonsul')->with('success', 'Status pembayaran berhasil diperbarui!');
    }


}
       

