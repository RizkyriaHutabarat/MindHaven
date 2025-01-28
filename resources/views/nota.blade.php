@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MindHaven | Transaksi</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
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
<body class="font-roboto">

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
    @section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-lg border-0 rounded-lg">
            <div class="bg-green-500 text-white text-center py-4 rounded-t-lg">
                <h4 class="text-xl font-bold">Nota Pembelian</h4>
            </div>
            <div class="p-6">
                <div class="mb-3">
                    <strong>Nama Pemesan:</strong> {{ $booking->user->nama }}
                </div>
                <div class="mb-3">
                    <strong>Email Pemesan:</strong> {{ $booking->user->email }}
                </div>
                <hr class="my-4">
                <div class="mb-3">
                    <strong>Nama Psikolog:</strong> {{ $booking->psikolog->nama }}
                </div>
                <div class="mb-3">
                    <strong>Paket Konsultasi:</strong> {{ $booking->paket->nama_paket }}
                </div>
                <div class="mb-3">
                    <strong>Harga:</strong> Rp{{ number_format($booking->paket->harga) }}
                </div>
                <div class="mb-3">
                    <strong>Tanggal dan Hari Konsultasi:</strong> 
                    {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l, d F Y') }}
                </div>
                <div class="mb-3">
                    <strong>Waktu Konsultasi:</strong> {{ $booking->jam }}
                </div>
                <div class="mb-3">
                    <strong>Deskripsi:</strong> {{ $booking->deskripsi }}
                </div>
                <div class="mb-3">
                    <strong>Metode Pembayaran:</strong> {{ $booking->metodepembayaran }}
                </div>
                <div class="mb-3">
                    @if($booking->link_meet)
                        <strong>Link Meet:</strong> {{ $booking->link_meet }}
                    @else
                        <strong>Link Meet:</strong> Kamu Memilih Offline
                    @endif
                </div>
                <div class="mb-3">
                    @if($booking->bukti_pembayaran)
                        <strong>Bukti Pembayaran: <a href="{{ asset('storage/bukti_pembayaran/' . $booking->bukti_pembayaran) }}" target="_blank">Lihat Bukti</a></strong>
                    @endif  
                </div>
                <div class="mb-3">
                    <strong>Status Pembayaran:</strong>
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white {{ $booking->status_pembayaran === 'lunas' ? 'bg-green-500' : 'bg-yellow-500' }} rounded">
                        {{ ucfirst($booking->status_pembayaran) }}
                    </span>
                </div>
                <hr class="my-4">
                <div class="text-center font-semibold">
                    Terima kasih telah melakukan booking konsultasi! 🎉
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</body>
</html>
