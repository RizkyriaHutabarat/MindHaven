<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan Psikolog</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
@extends('layouts.psikolog')

@section('title', 'Tambah Laporan Psikolog')

@section('content')
<div class="container">
    <h1>Tambah Laporan Psikolog</h1>

    <form action="{{ route('psikolog.laporan.store', $jadwal->id) }}" method="POST">
        @csrf
        <div class="mb-2">
            <label for="hasil">Hasil Pemeriksaan</label>
            <textarea name="hasil" id="hasil" class="form-control" placeholder="Hasil Pemeriksaan" required></textarea>
        </div>
        <div class="mb-2">
            <label for="deskripsi_laporan">Rekomendasi</label>
            <textarea name="deskripsi_laporan" id="deskripsi_laporan" class="form-control" placeholder="Rekomendasi" required></textarea>
        </div>
       
        <div class="mb-2">
            <label for="status_laporan">Status Laporan</label>
            <select name="status_laporan" id="status_laporan" class="form-control" required>
                <option value="pending" selected>Pending</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        <!-- <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('psikolog.laporan.index') }}';">Cancel</button> -->
    </form>
</div>
@endsection
</body>
</html>
