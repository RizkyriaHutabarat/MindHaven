@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MindHaven | Reschedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Nunito:wght@400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        /* Custom fade-in and slide animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up { animation: fadeInUp 2s ease-out; }
        .fade-in { animation: fadeIn 1.5s ease-out forwards; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .slide-in-left { animation: slideInLeft 1s ease-out forwards; }
        @keyframes slideInLeft { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        .slide-in-right { animation: slideInRight 1s ease-out forwards; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Disabled button style */
        .disabled-button {
            background-color: #d1d5db;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>
<body class="font-roboto">

    <nav class="bg-primary-200 py-2">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="logo text-2xl font-bold text-gray-800">MindHaven</div>
            <div class="flex space-x-6 items-center">
                <a class="text-gray-800 font-semibold hover:text-green-600" href="{{ route('dashboard') }}">Home</a>
                <a class="text-gray-800 hover:text-green-600" href="{{ route('user.jadwal') }}">Transaksi</a>
                <a href="#" class="nav-link" onclick="handleLogout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-6" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-6 absolute inset-0 flex flex-col items-center justify-center text-center p-4" role="alert">
        <div class="flex items-center space-x-3">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
            <p class="text-lg text-red-700 font-bold">Jam ini sudah dipesan!</p>
        </div>
        <p class="text-sm text-gray-700 mt-2">Silakan pilih waktunya di bawah jam ini yang tersedia.</p>

        <!-- Tombol Cancel di bawah dan lebih berwarna -->
        <a href="{{ route('user.jadwal') }}"
           class="mt-4 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-300 shadow-md">
           Cancel
        </a>
    </div>
@endif





    <div class="container py-5 px-6 mx-auto">
        <h2 class="text-2xl font-bold text-primary text-center">Update Jadwal Konsultasi</h2>
        <form action="{{ route('jadwalkonsul.update', $jadwal->id) }}" method="POST" onsubmit="return formatTanggal()">
            @csrf
            @method('PUT')

            <!-- Tanggal Konsultasi -->
            <div class="mb-4">
                <label for="tanggal" class="form-label fw-bold">Tanggal Konsultasi</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control shadow-sm" value="{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d') }}" >
                <input type="hidden" name="hari" id="hari" value="{{ $jadwal->hari }}"> <!-- Hidden field for day -->
            </div>

            <!-- Pilih Waktu Konsultasi -->
            <div class="mb-4">
                <label for="jam" class="form-label fw-bold">Pilih Waktu Konsultasi</label>
                <input type="hidden" name="jam" id="jam" value="{{ $jadwal->jam }}"> <!-- Hidden field for selected time -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Available Time Slots -->
                    <button type="button" class="time-slot-btn bg-green-600 text-white p-4 rounded-lg shadow-md hover:bg-green-700 transition duration-300" data-time="09:00"
                        {{ $jadwal->jam == '09:00' ? 'class=selected' : '' }}>
                        09:00
                    </button>
                    <button type="button" class="time-slot-btn bg-green-600 text-white p-4 rounded-lg shadow-md hover:bg-green-700 transition duration-300" data-time="11:00"
                        {{ $jadwal->jam == '11:00' ? 'class=selected' : '' }}>
                        11:00
                    </button>
                    <button type="button" class="time-slot-btn bg-green-600 text-white p-4 rounded-lg shadow-md hover:bg-green-700 transition duration-300" data-time="13:00"
                        {{ $jadwal->jam == '13:00' ? 'class=selected' : '' }}>
                        13:00
                    </button>
                    <button type="button" class="time-slot-btn bg-green-600 text-white p-4 rounded-lg shadow-md hover:bg-green-700 transition duration-300" data-time="15:00"
                        {{ $jadwal->jam == '15:00' ? 'class=selected' : '' }}>
                        15:00
                    </button>
                </div>
                <p id="selected-time" class="text-gray-700 font-semibold mt-3">
                    {{ $jadwal->jam ? 'Waktu yang dipilih: ' . $jadwal->jam : 'Belum memilih waktu.' }}
                </p>
            </div>

            <!-- Jenis Konsultasi -->
            <div class="mb-4">
                <label for="jenis_konsultasi" class="form-label fw-bold">Jenis Konsultasi</label>
                <div class="flex space-x-6">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="jenis_konsultasi" value="online" {{ $jadwal->jenis_konsultasi == 'online' ? 'checked' : '' }}>
                        <span>Online</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="jenis_konsultasi" value="offline" {{ $jadwal->jenis_konsultasi == 'offline' ? 'checked' : '' }}>
                        <span>Offline</span>
                    </label>
                </div>
            </div>

            <!-- Link Meet (Only shows if "Online" is selected) -->
            <div class="mb-4" id="link-meet-container" style="display: none;">
                <label for="link_meet" class="form-label">Link Google Meet</label>
                <input type="text" id="link_meet" name="link_meet" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-primary" id="submit-time" disabled>Simpan Perubahan</button>
        </form>
    </div>

<script>
    // JavaScript to handle time slot selection
    const timeSlots = document.querySelectorAll('.time-slot-btn');
    const selectedTimeText = document.getElementById('selected-time');
    const submitButton = document.getElementById('submit-time');
    const timeInput = document.getElementById('jam'); // Hidden time input

    timeSlots.forEach(button => {
        button.addEventListener('click', () => {
            const selectedTime = button.getAttribute('data-time');
            selectedTimeText.textContent = `Waktu yang dipilih: ${selectedTime}`;

            // Set the hidden input with the selected time
            timeInput.value = selectedTime;

            // Enable submit button
            submitButton.disabled = false;
        });
    });

    // Update the day when the date is changed
    document.getElementById('tanggal').addEventListener('change', formatTanggal);

    function formatTanggal() {
        const tanggalInput = document.getElementById('tanggal').value; // Get the date input
        const hariInput = document.getElementById('hari'); // Hidden input for day

        if (tanggalInput) {
            const tanggal = new Date(tanggalInput);
            const options = { weekday: 'long' }; // Get the weekday name

            const formattedDay = tanggal.toLocaleDateString('id-ID', options); // Format to day name
            hariInput.value = formattedDay; // Set the value to hidden input
        } else {
            alert("Pilih tanggal konsultasi terlebih dahulu.");
            return false;
        }

        return true;
    }

    // Show Google Meet link when 'Online' is selected
    document.querySelectorAll('input[name="jenis_konsultasi"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const linkMeetContainer = document.getElementById('link-meet-container');
            const linkMeetInput = document.getElementById('link_meet');
            const meetLinks = [
                'https://meet.google.com/hkw-onac-ntt',
                'https://meet.google.com/fmt-nnks-ohp',
                'https://meet.google.com/tba-nqxw-zbc',
                'https://meet.google.com/gte-rwbi-rio'
            ];

            if (this.value === 'online') {
                // Show the meet link container and set a random link
                const randomLink = meetLinks[Math.floor(Math.random() * meetLinks.length)];
                linkMeetInput.value = randomLink;
                linkMeetContainer.style.display = 'block';
            } else {
                // Hide the link container when offline is selected
                linkMeetInput.value = '';
                linkMeetContainer.style.display = 'none';
            }
        });
    });
</script>
<script>
        function handleLogout() {
    // Hapus token dari localStorage
    localStorage.removeItem('admin_token');

    // Submit form logout
    document.getElementById('logout-form').submit();
}
    </script>

@endsection
