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

@section('title', 'Edit Laporan Psikolog')

@section('content')
<div class="container">
    <h1>Edit Laporan Psikolog</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form untuk mengedit laporan -->
    <form action="{{ route('psikolog.laporan.update', $laporan->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Menggunakan method PUT untuk update -->

        <div class="mb-2">
            <label for="hasil" class="form-label">Hasil Pemeriksaan</label>
            <textarea name="hasil" class="form-control" placeholder="Hasil Pemeriksaan">{{ old('hasil', $laporan->hasil) }}</textarea>
        </div>

        <div class="mb-2">
            <label for="deskripsi_laporan" class="form-label">Rekomendasi</label>
            <textarea name="deskripsi_laporan" class="form-control" placeholder="Rekomendasi" required>{{ old('deskripsi_laporan', $laporan->deskripsi_laporan) }}</textarea>
        </div>
        

        <div class="mb-2">
            <label for="status_laporan" class="form-label">Status Laporan</label>
            <select name="status_laporan" class="form-control" required>
                <option value="pending" {{ $laporan->status_laporan == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="selesai" {{ $laporan->status_laporan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ $laporan->status_laporan == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui Laporan</button>
    </form>
</div>
@endsection
</body>
</html>