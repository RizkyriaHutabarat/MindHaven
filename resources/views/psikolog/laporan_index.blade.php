<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Psikolog</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
@extends('layouts.psikolog')

@section('title', 'Kelola Laporan Psikolog')

@section('content')
<div class="container">
    <h1>Daftar Jadwal Konsultasi</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($jadwals->isEmpty())
        <p>Belum ada jadwal konsultasi yang tersedia.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Klien</th>
                    <th>Nama Paket</th>
                    <th>Deskripsi Pasien</th>
                    <th>Hari & Jam</th>
                    <th>Link Meet</th>
                    <th>Hasil Pemeriksaan</th>
                    <th>Rekomendasi</th>
                    <th>Status Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jadwal->user_name }}</td>
                    <td>{{ $jadwal->nama_paket }}</td>
                    <td>{{ $jadwal->deskripsi}}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}
                        {{ $jadwal->jam }}
                    </td>
                    <td>
                        @if($jadwal->link_meet)
                            <p class="text-md text-gray-600">Link Meet: <strong>{{ $jadwal->link_meet }}</strong></p>
                        @else
                            <p class="text-md text-gray-600">Link Meet: <strong>Klien Memilih offline</strong></p>
                        @endif
                    </td>
                    <!-- Menampilkan deskripsi, hasil, dan status laporan -->
                    <td>
                        @php
                            $laporan = $laporans->firstWhere('id_jadwalkonsul', $jadwal->id);
                        @endphp
                        {{ $laporan ? $laporan->deskripsi_laporan : '-' }}
                    </td>
                    <td>
                        {{ $laporan ? $laporan->hasil : '-' }}
                    </td>
                    <td>
                        <!-- Check if laporan has status, if not, display a default color -->
                        <span class="badge 
                            @if($laporan && $laporan->status_laporan == 'pending') badge-warning
                            @elseif($laporan && $laporan->status_laporan == 'selesai') badge-success
                            @elseif($laporan && $laporan->status_laporan == 'In Progress') badge-primary
                            @else badge-secondary
                            @endif">
                            {{ $laporan ? ucfirst($laporan->status_laporan) : '-' }}
                        </span>
                    </td>

                    <td>
                        @if($laporan)
                            <!-- Tombol Edit jika laporan sudah ada -->
                            <a href="{{ route('psikolog.laporan.edit', $laporan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @else
                            <!-- Tombol Tambah jika laporan belum ada, link ke form -->
                            <a href="{{ route('psikolog.laporan.forms', ['jadwalId' => $jadwal->id]) }}" class="btn btn-primary btn-sm">Buat Laporan</a>

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
</body>
</html>
