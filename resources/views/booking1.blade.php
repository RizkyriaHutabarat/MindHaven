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
                <a class="text-gray-800 hover:text-green-600" href="{{ route('user.jadwal') }}">Transaksi</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
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

                <form action="{{ route('jadwal.store') }}" method="POST" onsubmit="return formatTanggal()">
                    @csrf
                    
                    <!-- Pilih Psikolog -->
                    <div class="mb-6">
                        <label for="id_psikologs" class="block text-lg font-semibold">Pilih Psikolog</label>
                        <select name="id_psikologs" id="id_psikologs" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="" disabled selected>-- Pilih Psikolog --</option>
                            @foreach($psikologs as $psikolog)
                                <option value="{{ $psikolog->id }}">{{ $psikolog->nama }}</option>
                            @endforeach
                        </select>
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
            if (!button.disabled) {
                const selectedTime = button.getAttribute('data-time');

                // Memperbarui tampilan waktu dan mengisi input tersembunyi
                selectedTimeText.textContent = `Jam yang dipilih: ${selectedTime}`;
                timeInput.value = selectedTime;

                // Mengaktifkan tombol submit
                submitButton.disabled = false;
            }
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
    // Duplicate declaration removed
</script>
@endsection
