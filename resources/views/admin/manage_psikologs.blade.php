<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manage Psikolog</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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
    <div class="sidebar d-none d-md-block">
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
                    <i class="fas fa-user-md"></i> Manage Psikolog
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.manage_pakets') }}">
                    <i class="fas fa-box"></i> Manage Paket
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.manage_jadwalkonsul') }}">
                    <i class="fas fa-calendar-alt"></i> Manage Jadwal Konsultasi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin.manage-laporan-psikolog') }}">
                    <i class="fas fa-file-alt"></i> Laporan Psikolog
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Header -->
        <div class="header mb-4">
            <h2>Manage Psikolog</h2>
        </div>
            <!-- Alert -->
                <!-- Flash Message -->
                @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Psikolog Table -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Psikolog List</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Spesialisasi</th>
                            <th>Bio</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($psikologs as $psikolog)
                        <tr>
                            <td>{{ $psikolog->nama }}</td>
                            <td>{{ $psikolog->email }}</td>
                            <td>{{ $psikolog->spesialisasi }}</td>
                            <td>{{ $psikolog->bio }}</td>
                            <td>
                                <!-- Edit Psikolog -->
                                <a href="{{ route('admin.edit_psikolog', $psikolog->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Psikolog -->
                                <!-- <form action="{{ route('admin.delete_psikolog', $psikolog->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this psychologist?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form> -->
                                <!-- Include SweetAlert2 CDN -->
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                <!-- Hapus Jadwal -->
                                <form action="{{ route('admin.delete_psikolog', $psikolog->id) }}" method="POST" style="display:inline;" id="deleteForm_{{ $psikolog->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteAlert({{ $psikolog->id }})">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>

                                <script>
                                function showDeleteAlert(id) {
                                    Swal.fire({
                                    title: 'Apakah Anda yakin?',
                                    text: "Psikolog ini akan dihapus secara permanen!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Hapus!',
                                    cancelButtonText: 'Batal'
                                    }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Jika pengguna menekan Hapus, submit form untuk menghapus
                                        document.getElementById('deleteForm_' + id).submit();
                                        Swal.fire(
                                        'Dihapus!',
                                        'Psikolog konsultasi telah dihapus.',
                                        'success'
                                        );
                                    }
                                    });
                                }
                                </script>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap and JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
