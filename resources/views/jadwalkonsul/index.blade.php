@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Jadwal Konsultasi</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Psikolog</th>
                <th>Paket</th>
                <th>Jam</th>
                <th>Hari</th>
                <th>Deskripsi</th>
                <th>Metode Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jadwal->id_psikologs }}</td>
                    <td>{{ $jadwal->id_pakets }}</td>
                    <td>{{ $jadwal->jam }}</td>
                    <td>{{ $jadwal->hari }}</td>
                    <td>{{ $jadwal->deskripsi }}</td>
                    <td>{{ $jadwal->metodepembayaran }}</td>
                    <td>
                        <a href="{{ route('jadwalkonsul.edit', $jadwal->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada jadwal konsultasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection