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
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <div class="d-flex justify-content-between">
                <h2>Manage Paket</h2>
            </div>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Button to Add New Paket -->
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addPaketModal">Tambah Paket</button>

        <!-- Paket Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Paket</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pakets as $index => $paket)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $paket->nama_paket }}</td>
                    <td>{{ $paket->deskripsi }}</td>
                    <td>
                        @if($paket->gambar)
                            <img src="{{ asset('storage/' . $paket->gambar) }}" alt="{{ $paket->nama_paket }}" style="width: 100px">
                        @else
                            No image available
                        @endif
                    </td>
                    <!-- Format harga with thousands separator -->
                    <td>Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPaketModal{{ $paket->id }}">Edit</button>
                        <!-- <form action="{{ route('admin.delete_paket', $paket->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus paket ini?')">Delete</button>
                        </form> -->
                        <!-- Include SweetAlert2 CDN -->
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                        <!-- Hapus Jadwal -->
                        <form action="{{ route('admin.delete_paket', $paket->id) }}" method="POST" style="display:inline;" id="deleteForm_{{ $paket->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteAlert({{ $paket->id }})">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>

                        <script>
                        function showDeleteAlert(id) {
                            Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Paket ini akan dihapus secara permanen!",
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
                                'Paket konsultasi telah dihapus.',
                                'success'
                                );
                            }
                            });
                        }
                        </script>
                    </td>
                </tr>

                <!-- Edit Paket Modal -->
                <div class="modal fade" id="editPaketModal{{ $paket->id }}" tabindex="-1" aria-labelledby="editPaketModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPaketModalLabel">Edit Paket</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.update_paket', $paket->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nama_paket" class="form-label">Nama Paket</label>
                                        <input type="text" class="form-control" name="nama_paket" value="{{ $paket->nama_paket }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" rows="3">{{ $paket->deskripsi }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label">Upload Gambar</label>
                                        <input type="file" class="form-control" name="gambar">
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <input type="text" class="form-control" name="harga" value="{{ $paket->harga }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <!-- Add Paket Modal -->
        <div class="modal fade" id="addPaketModal" tabindex="-1" aria-labelledby="addPaketModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaketModalLabel">Tambah Paket Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.store_paket') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_paket" class="form-label">Nama Paket</label>
                                <input type="text" class="form-control" name="nama_paket" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Upload Gambar</label>
                                <input type="file" class="form-control" name="gambar" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="text" class="form-control" name="harga" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambah Paket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
