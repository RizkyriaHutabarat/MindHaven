@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Manage Laporan Psikolog</h2>
        @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

        <form action="{{ route('laporan.download') }}" method="GET" class="mb-4">
            <div class="form-group">
                <label for="status_laporan">Pilih Status Laporan:</label>
                <select name="status_laporan" id="status_laporan" class="form-control" required>
                    <option value="pending" {{ old('status_laporan') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="selesai" {{ old('status_laporan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ old('status_laporan') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Download Laporan</button>
        </form>

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
