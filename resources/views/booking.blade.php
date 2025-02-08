@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MindHaven | Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up { animation: fadeInUp 0.5s ease-out; }
        .disabled-button {
            background-color: #d1d5db;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>
<body class="font-roboto bg-gray-50">

<nav class="bg-primary-200 py-2">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="logo text-2xl font-bold text-gray-800">MindHaven</div>
            <div class="flex space-x-6 items-center">
                <a class="text-gray-800 font-semibold hover:text-green-600" href="{{ route('dashboard') }}">Home</a>
                <a class="text-gray-800 hover:text-green-600" href="{{ route('user.jadwal') }}">Jadwal</a>
                <a href="#" class="nav-link" onclick="handleLogout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-12">
        <div class="max-w-lg mx-auto">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-green-500 text-white text-center py-4 rounded-t-lg">
                    <h3 class="text-2xl font-bold">Form Booking Konsultasi</h3>
                </div>
                <div class="p-8">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-6" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


                <form action="{{ route('jadwal.store') }}" method="POST" onsubmit="return formatTanggal()">
                    @csrf

                    <!-- Pilih Psikolog -->
                    <div class="mb-6">
                        <label for="id_psikologs" class="block text-lg font-semibold">Psikolog</label>
                        <p class="font-bold">{{ $psikologs->firstWhere('id', $selectedPsikolog)->nama }}</p>
                        <input type="hidden" name="id_psikologs" value="{{ $selectedPsikolog }}">
                    </div>

                    <!-- Pilih Paket yang sudah dipilih -->
                    <div class="mb-6">
                        <label for="id_pakets" class="block text-lg font-semibold">Pilih Paket</label>
                        <select name="id_pakets" id="id_pakets" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="" disabled selected>-- Pilih Paket Konsultasi --</option>
                            @foreach($pakets as $paket)
                                <!-- Set the selected attribute if this is the selected package -->
                                <option value="{{ $paket->id }}" {{ isset($selectedPaket) && $selectedPaket->id == $paket->id ? 'selected' : '' }} data-harga="{{ $paket->harga }}">
                                    {{ $paket->nama_paket }}
                                </option>
                            @endforeach
                        </select>
                        <p id="harga-paket" class="mt-3 text-indigo-600 text-lg font-semibold">Harga: Rp {{ number_format($selectedPaket->harga ?? 0, 0, ',', '.') }}</p>
                    </div>

                        <!-- Tanggal Konsultasi -->
                        <div class="mb-6">
                            <label for="tanggal" class="block text-lg font-semibold">Tanggal Konsultasi</label>
                            <input type="date" name="tanggal" id="tanggal" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <input type="hidden" name="hari" id="hari">
                            <p id="selected-date" class="text-gray-700 font-semibold mt-3">Belum memilih tanggal.</p>
                        </div>

                        <!-- Pilih Waktu Konsultasi -->
                        <div class="mb-6">
                            <label for="jam" class="block text-lg font-semibold">Pilih Waktu Konsultasi</label>
                            <input type="hidden" name="jam" id="jam"> <!-- Hidden field for selected time -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @php
                                    $timeSlots = ['09:00', '11:00', '13:00', '15:00'];
                                @endphp

                        @foreach($timeSlots as $time)
                            @php
                                $isBooked = in_array($time, $bookedTimes);
                            @endphp

                            <button type="button"
                                    class="time-slot-btn {{ $isBooked ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 text-white' }} p-4 rounded-lg shadow-md {{ $isBooked ? 'pointer-events-none' : 'hover:bg-green-700' }} transition duration-300"
                                    data-time="{{ $time }}"
                                    {{ $isBooked ? 'disabled' : '' }}>
                                {{ $time }}
                            </button>
                        @endforeach

                            </div>
                            <p id="selected-time" class="text-gray-700 font-semibold mt-3">Belum memilih waktu.</p>
                        </div>
                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <label for="deskripsi" class="block text-lg font-semibold">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="4" placeholder="Jelaskan kebutuhan konsultasi Anda" required></textarea>
                        </div>

                    <!-- Jenis Konsultasi (Online atau Offline) -->
                    <div class="mb-6">
                        <label for="jenis_konsultasi" class="block text-lg font-semibold">Jenis Konsultasi</label>
                        <select name="jenis_konsultasi" id="jenis_konsultasi" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>

                    <!-- Link Meet untuk Konsultasi Online -->
                    <div class="mb-6">
                        <label for="link_meet" class="block text-lg font-semibold">Link Meet</label>
                        <input type="text" name="link_meet" id="link_meet" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm" readonly placeholder="Link akan otomatis terisi jika konsultasi online">
                    </div>

                    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jenisKonsultasiSelect = document.getElementById('jenis_konsultasi');
            const linkMeetInput = document.getElementById('link_meet');
            const meetLinks = [
                'https://meet.google.com/hkw-onac-ntt',
                'https://meet.google.com/fmt-nnks-ohp',
                'https://meet.google.com/tba-nqxw-zbc',
                'https://meet.google.com/gte-rwbi-rio'
            ];

            jenisKonsultasiSelect.addEventListener('change', function () {
                if (this.value === 'online') {
                    const randomLink = meetLinks[Math.floor(Math.random() * meetLinks.length)];
                    linkMeetInput.value = randomLink;
                } else {
                    linkMeetInput.value = ''; // Kosongkan jika offline
                }
            });

        });
    </script>

                            <!-- Metode Pembayaran -->
                            <div class="mb-6">
                                <label for="metodepembayaran" class="block text-lg font-semibold">Metode Pembayaran</label>
                                <select name="metodepembayaran" id="metodepembayaran" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                    <option value="Kartu Kredit">Kartu Kredit</option>
                                    <option value="COD">COD</option>
                                </select>
                            </div>

                            <!-- Nomor Pembayaran -->
                            <div id="nomor-pembayaran-container" class="mb-6" style="display: none;">
                                <label for="nomor-pembayaran" class="block text-lg font-semibold">Nomor Pembayaran</label>
                                <p id="nomor-pembayaran" class="text-indigo-600 font-bold"></p>
                            </div>

                            <script>
                                const metodePembayaranDropdown = document.getElementById('metodepembayaran');
                                const nomorPembayaranContainer = document.getElementById('nomor-pembayaran-container');
                                const nomorPembayaranText = document.getElementById('nomor-pembayaran');

                                // Nomor pembayaran berdasarkan metode
                                const nomorPembayaran = {
                                    "Transfer Bank": "1234567890 (BCA)",
                                    "E-Wallet": "08123456789 (Dana/Gopay)",
                                    "Kartu Kredit": "Kartu Anda akan ditagihkan sesuai detail transaksi.",
                                    "COD": "Bayar di tempat saat konsultasi."
                                };

                                metodePembayaranDropdown.addEventListener('change', function () {
                                    const selectedMethod = this.value;
                                    if (nomorPembayaran[selectedMethod]) {
                                        nomorPembayaranContainer.style.display = 'block';
                                        nomorPembayaranText.textContent = nomorPembayaran[selectedMethod];
                                    } else {
                                        nomorPembayaranContainer.style.display = 'none';
                                        nomorPembayaranText.textContent = '';
                                    }
                                });
                            </script>

                        <!-- Bukti Pembayaran -->
                        <div class="mb-6">
                            <label for="bukti_pembayaran" class="block text-lg font-semibold">Unggah Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="form-text text-muted">File yang diperbolehkan: JPG, JPEG, PNG, PDF (maks. 2MB)</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-6">
                            <button type="submit" class="w-full bg-indigo-600 text-white p-3 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300" id="submit-time" disabled>Booking Sekarang</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to Handle Time Slot Selection -->
    <script>
    // Fungsi untuk menampilkan hari dan tanggal dalam bahasa Indonesia
    document.getElementById('tanggal').addEventListener('change', () => {
        const tanggalInput = document.getElementById('tanggal').value;
        const hariInput = document.getElementById('hari');
        const selectedDateText = document.getElementById('selected-date');

        if (tanggalInput) {
            const tanggal = new Date(tanggalInput);
            const hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' });
            const tanggalFormatted = tanggal.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });

            // Mengisi field tersembunyi dan menampilkan hasil
            hariInput.value = hari;
            selectedDateText.textContent = `Tanggal yang dipilih: ${hari}, ${tanggalFormatted}`;
        }
    });

// Handle pemilihan waktu konsultasi
const timeSlots = document.querySelectorAll('.time-slot-btn');
const selectedTimeText = document.getElementById('selected-time');
const submitButton = document.getElementById('submit-time');
const timeInput = document.getElementById('jam');

timeSlots.forEach(button => {
    button.addEventListener('click', () => {
        if (button.disabled) {
            // Jika waktu sudah dipesan, tampilkan alert
            alert('Jam ini sudah dipesan, silakan pilih waktu lain.');
            return;  // Tidak lanjutkan proses jika waktu sudah dipesan
        }

        const selectedTime = button.getAttribute('data-time');

        // Memperbarui tampilan waktu dan mengisi input tersembunyi
        selectedTimeText.textContent = `Jam yang dipilih: ${selectedTime}`;
        timeInput.value = selectedTime;

        // Mengaktifkan tombol submit
        submitButton.disabled = false;
    });
});
</script>


</script>

    <script>
        const paketSelect = document.getElementById('id_pakets');
        const hargaPaketText = document.getElementById('harga-paket');

        paketSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');

            if (harga) {
                hargaPaketText.textContent = `Harga: Rp ${parseInt(harga).toLocaleString('id-ID')}`;
            } else {
                hargaPaketText.textContent = 'Harga: -';
            }
        });
    </script>
     <script>
        function handleLogout() {
    // Hapus token dari localStorage
    localStorage.removeItem('user_token');

    // Submit form logout
    document.getElementById('logout-form').submit();
}
    </script>
<script>
    // Duplicate declaration removed
</script>
@endsection
