<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Manage Users</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
            <h2>Manage Users</h2>
        </div>
                 <!-- Success/Error Message -->
                @if(session('success'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-500 rounded p-4">
                    {{ session('success') }}
                </div>
                @elseif(session('error'))
                    <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-500 rounded p-4">
                        {{ session('error') }}
                    </div>
                @endif

        <!-- User Table -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">User List</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Nomor Telepon</th>
                            <th>Tipe User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->jenis_kelamin }}</td>
                            <td>{{ $user->tanggal_lahir }}</td>
                            <td>{{ $user->nomor_telepon }}</td>
                            <td>{{ $user->tipe_user }}</td>
                            <td>
                                <a href="{{ route('admin.edit_user', $user->id_user) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.delete_user', $user->id_user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash-alt"></i> Delete
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

    <!-- Bootstrap and JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
