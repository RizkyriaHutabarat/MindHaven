@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Manage Laporan Psikolog</h2>
        
        <!-- Tabel Responsif -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle">
                <thead class="table-bordered">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Psikolog</th>
                        <th>Nama Pasien</th>
                        <th>Deskripsi Pasien</th>
                        <th>Tanggal & Jam</th>
                        <th>Hasil Pemeriksaan</th>
                        <th>Rekomendasi</th>
                        <th>Status Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanPsikologs as $laporan)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $laporan->psikolog->nama }}</td>
                            <td>{{ $laporan->user->nama }}</td>
                            <td>{{ $laporan->jadwalkonsul->deskripsi }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($laporan->jadwalkonsul->tanggal)->translatedFormat('l, d F Y') }} <br>
                                <span class="badge bg-info text-dark">{{ $laporan->jadwalkonsul->jam }}</span>
                            </td>
                            <td>{{ $laporan->hasil }}</td>
                            <td>{{ $laporan->deskripsi_laporan }}</td>
                            <td class="text-center">
                                <span class="badge 
                                    @if($laporan->status_laporan == 'pending') bg-warning text-dark
                                    @elseif($laporan->status_laporan == 'selesai') bg-success
                                    @else bg-secondary @endif">
                                    {{ ucfirst($laporan->status_laporan) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<!-- Script Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
