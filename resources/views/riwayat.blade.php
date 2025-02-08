@extends('layouts.app') <!-- Sesuaikan dengan layout utama Anda -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MindHaven</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Nunito:wght@400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
    @keyframes fadeInUp {
        from {
        opacity: 0;
        transform: translateY(20px);
        }
        to {
        opacity: 5;
        transform: translateY(0);
        }
    }
    .fade-in-up {
        animation: fadeInUp 5s ease-out;
    }
    .fade-in {
    animation: fadeIn 1.5s ease-out forwards;
    }

    @keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
    }

    .slide-in-left {
    animation: slideInLeft 1s ease-out forwards;
    }

    @keyframes slideInLeft {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
    }

    .slide-in-right {
    animation: slideInRight 1s ease-out forwards;
    }

    @keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
    }
    </style>
</head>
<body class="font-roboto">
        <nav class="bg-primary-200 py-2">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="logo text-2xl font-bold text-gray-800">MindHaven</div>
            <div class="relative flex-1 max-w-sm hidden lg:block">
                <input class="pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 w-full" placeholder="Search plants..." type="text" />
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
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

        @extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-center text-2xl font-bold mb-6">Riwayat Konsultasi</h1>

        @if($riwayat->isEmpty())
            <p class="text-center text-gray-600">Belum ada riwayat konsultasi.</p>
        @else
         <!-- Hapus form filter status -->
        <!-- <div class="flex justify-end mb-4">
            <form action="{{ route('download.laporan') }}" method="GET" class="flex items-center">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                    <i class="fas fa-download mr-2"></i> Download Laporan
                </button>
            </form>
        </div> -->

            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Psikolog</th>
                        <th class="py-2 px-4 border-b">Tanggal</th>
                        <th class="py-2 px-4 border-b">Paket</th>
                        <!-- <th class="py-2 px-4 border-b">Deskripsi Klien</th>
                        <th class="py-2 px-4 border-b">Deskripsi Laporan</th>
                        <th class="py-2 px-4 border-b">Hasil</th> -->
                        <th class="py-2 px-4 border-b">Status</th>
                        <th class="py-2 px-4 border-b">Download Laporan</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($riwayat as $item)
        <tr>
            <td class="py-2 px-4 border-b">{{ $item->psikolog ? $item->psikolog->nama : 'N/A' }}</td>
            <td class="py-2 px-4 border-b">{{ $item->jadwalKonsul ? \Carbon\Carbon::parse($item->jadwalKonsul->tanggal)->translatedFormat('l, d F Y') : 'N/A' }}</td>
            <td class="py-2 px-4 border-b">{{ $item->paket ? $item->paket->nama_paket : 'N/A' }}</td>
            <!-- <td class="py-2 px-4 border-b">{{ $item->jadwalKonsul ? $item->jadwalKonsul->deskripsi : 'N/A'}}</td> -->
            <!-- <td class="py-2 px-4 border-b">{{ $item->deskripsi_laporan }}</td>
            <td class="py-2 px-4 border-b">{{ Str::limit($item->hasil, 1000) }}</td> -->
            <td class="py-2 px-4 border-b">
                @if($item->status_laporan === 'pending')
                    <span class="text-red-600 font-bold">Pending</span>
                @elseif($item->status_laporan === 'selesai')
                    <span class="text-green-600 font-bold">Selesai</span>
                @else
                    <span class="text-gray-600">Tidak Diketahui</span>
                @endif
            </td>
            <td>
    <!-- Update form to pass item ID -->
    <form action="{{ route('download.laporan', $item->id) }}" method="GET">
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
        <i class="fas fa-download mr-2"></i> Download
    </button>
</form>

</td>

        </tr>
    @endforeach
</tbody>

            </table>
        @endif
    </div>
@endsection
<script>
        function handleLogout() {
    // Hapus token dari localStorage
    localStorage.removeItem('admin_token');

    // Submit form logout
    document.getElementById('logout-form').submit();
}
    </script>
