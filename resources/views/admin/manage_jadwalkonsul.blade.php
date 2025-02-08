<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manage Paket</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            color: white;
        }
        .sidebar .nav-item {
            margin: 20px 0;
        }
        .sidebar .nav-link {
            color: white;
            padding: 15px;
            font-size: 16px;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 10px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center mt-3">Dashboard</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.manage_users') }}">
                    <i class="fas fa-users"></i> Manage User
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.manage_psikologs') }}">
                    <i class="fas fa-cogs"></i> Manage Psikolog
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.manage_pakets') }}">
                    <i class="fas fa-box"></i> Manage Paket
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.manage_jadwalkonsul') }}">
                    <i class="fas fa-calendar-alt"></i> Manage Jadwal Konsultasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.manage-laporan-psikolog') }}">
                    <i class="fas fa-file-alt"></i> Laporan Psikolog
                </a>
            </li>
            <li class="nav-item">
            <a href="#" class="nav-link" onclick="handleLogout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            </li>
        </ul>
    </div>

<div class="content">
        <div class="header">
            <div class="d-flex justify-content-between">
                <h2>Manage Jadwal Konsul & verifikasi Pembayaran</h2>
            </div>
        </div>
                <!-- Flash Message -->
                @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="container-fluid">
            <div class="row">


<!-- Jadwal Konsultasi Table -->
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Jadwal Konsultasi List</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemesan</th>
                        <th>Nama Psikolog</th>
                        <th>Paket Konsultasi</th>
                        <th>Tanggal Konsultasi</th>
                        <th>Waktu Konsultasi</th>
                        <th>Deskripsi</th>
                        <th>Link Meet</th>
                        <th>Metode Pembayaran</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status Pembayaran</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwalKonsuls as $jadwal)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jadwal->user->nama }}</td>
                            <td>{{ $jadwal->psikolog->nama }}</td>
                            <td>{{ $jadwal->paket->nama_paket }}</td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</td>
                            <td>{{ $jadwal->jam }}</td>
                            <td>{{ $jadwal->deskripsi }}</td>
                            <td>
                                @if($jadwal->link_meet)
                                    <strong></strong> {{ $jadwal->link_meet }}
                                @else
                                    <strong></strong> Kamu Memilih Offline
                                @endif
                            </td>
                            <td>{{ $jadwal->metodepembayaran }}</td>
                            <td>
                                @if($jadwal->bukti_pembayaran)
                                    <a href="{{ asset('storage/bukti_pembayaran/' . $jadwal->bukti_pembayaran) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span>Belum ada bukti</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $jadwal->status_pembayaran == 'lunas' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($jadwal->status_pembayaran) }}
                                </span>
                            </td>
                            <td>
                                <!-- <a href="{{ route('admin.edit_jadwalkonsul', $jadwal->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a> -->
                                <form action="{{ route('admin.delete_jadwalkonsul', $jadwal->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this schedule?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                                <!-- Status Pembayaran Button -->
                                <form action="{{ route('admin.update_status_pembayaran', $jadwal->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-sync"></i> Ubah Status
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

