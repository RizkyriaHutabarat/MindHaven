@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MindHaven | Transaksi</title>
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
        .bg-gray-200 {
            background-color: #e5e7eb;
        }
        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="font-roboto bg-gray-100">

    <nav class="bg-primary-200 py-3 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="logo text-2xl font-bold text-gray-800">MindHaven</div>
            <div class="flex space-x-6 items-center">
                <a class="text-gray-800 font-semibold hover:text-green-600" href="{{ route('dashboard') }}">Home</a>
                <a class="text-gray-800 hover:text-green-600" href="{{ route('user.jadwal') }}">Jadwal</a>
                <a class="text-gray-800 hover:text-green-600" href="{{ route('user.riwayat') }}">Laporan</a>
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
        <div id="alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative fade-in-up mb-4">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="closeAlert()">
                <span class="text-green-500">&times;</span>
            </button>
        </div>
    @endif

    <div class="container py-10 px-6 mx-auto">
        <h1 class="text-center text-3xl mb-6 text-primary font-semibold">Jadwal Konsultasi Anda</h1>

        @if($jadwals->isEmpty())
            <p class="text-center text-lg text-gray-500">Belum ada jadwal konsultasi yang terdaftar.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($jadwals as $jadwal)
                    @php
                        $isToday = \Carbon\Carbon::parse($jadwal->tanggal)->isToday();
                    @endphp
                    <div class="card bg-white rounded-lg shadow-lg p-6 fade-in-up {{ $jadwal->status_laporan == 'selesai' ? 'bg-gray-200' : '' }}">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $jadwal->psikolog->nama }}</h3>
                        <p class="text-md text-gray-600">Paket: <strong>{{ $jadwal->paket->nama_paket }}</strong></p>
                        <p class="text-md text-gray-600">Tanggal Konsultasi: <strong>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</strong></p>
                        <p class="text-md text-gray-600">Jam: <strong>{{ $jadwal->jam }}</strong></p>
                        <p class="text-md">
                            Status Pembayaran:
                            @if($jadwal->status_pembayaran == 'verified')
                                <span class="text-green-600 font-semibold">Verified</span>
                            @elseif($jadwal->status_pembayaran == 'pending')
                                <span class="text-red-600 font-semibold">Pending</span>
                            @else
                                <span class="text-gray-600 font-semibold">{{ ucfirst($jadwal->status_pembayaran) }}</span>
                            @endif
                        </p>
                        @if($jadwal->status_pembayaran == 'verified')
                            @if($jadwal->link_meet)
                                <p class="text-md text-gray-600">Link Meet: <a href="{{ $jadwal->link_meet }}" class="text-blue-500 hover:underline">{{ $jadwal->link_meet }}</a></p>
                            @else
                                <p class="text-md text-gray-600">Link Meet: <strong>Kamu Memilih Offline</strong></p>
                            @endif
                        @else
                            <p class="text-md text-red-500">Link Meet: <strong>Admin Akan Memverifikasi Pembayaran Anda</strong></p>
                        @endif


                        <p class="text-md text-gray-600">Deskripsi: <strong>{{ $jadwal->deskripsi }}</strong></p>
                        <p class="text-md text-gray-600">Metode Pembayaran: <strong>{{ $jadwal->metodepembayaran }}</strong></p>

                        <div class="mt-4 text-center">
                            @php
                                $isPast = \Carbon\Carbon::parse($jadwal->tanggal)->isPast();
                            @endphp

                            @if($jadwal->status_laporan == 'selesai' || $isToday || $isPast)
                                <button class="btn btn-warning disabled-button bg-gray-400 text-white px-4 py-2 rounded-md shadow-md">
                                    <i class="fas fa-calendar-times mr-2"></i> Reschedule Tidak Tersedia
                                </button>
                            @else
                                <a href="{{ route('jadwalkonsul.edit', $jadwal->id) }}"
                                class="btn btn-warning bg-yellow-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-yellow-600 hover:shadow-lg transition duration-300 transform hover:scale-105">
                                    <i class="fas fa-calendar-check mr-2"></i> Reschedule
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function closeAlert() {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }
    </script>
     <script>
        function handleLogout() {
    // Hapus token dari localStorage
    localStorage.removeItem('admin_token');

    // Submit form logout
    document.getElementById('logout-form').submit();
}
    </script>

</body>
</html>
@endsection
