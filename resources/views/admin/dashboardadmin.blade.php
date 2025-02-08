<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
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
        .card {
            margin: 20px 0;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .card-body {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center mt-3">Dashboard</h3>
          @if (session('admin_token'))
    <<script>
        localStorage.setItem('admin_token', '{{ session('admin_token') }}');
    </script>
    @endif
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

   <!-- Main Content -->
   <div class="content">
        <!-- Header -->
        <div class="header">
            <div class="d-flex justify-content-between">
                <h2>Welcome to the Dashboard</h2>
                <div>
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-user-circle ml-3"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <!-- Card 1 -->
            @php
            use App\Models\User;
            use App\Models\Psikolog;
            use App\Models\JadwalKonsul;

            // Menghitung jumlah user
            $totalUsers = User::count();
            // Menghitung jumlah psikolog
            $totalPsikolog = Psikolog::count();
            // Menghitung jumlah jadwal konsultasi
            $totalJadwalKonsultasi = JadwalKonsul::count();
            @endphp

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        Total Users
                    </div>
                    <div class="card-body text-center">
                        <h5>{{ $totalUsers }}</h5>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        Total Psikolog
                    </div>
                    <div class="card-body text-center">
                        <h5>{{ $totalPsikolog }}</h5>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        Total Jadwal Konsultasi
                    </div>
                    <div class="card-body text-center">
                        <h5>{{ $totalJadwalKonsultasi }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status Overview -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        Status Pembayaran
                    </div>
                    <div class="card-body text-center">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <<script>
        var ctx = document.getElementById('paymentStatusChart').getContext('2d');
        var paymentStatusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Verified'],
                datasets: [{
                    label: 'Payment Status',
                    data: [@json($pendingCount), @json($verifiedCount)],
                    backgroundColor: ['#f39c12', '#2ecc71'],
                    borderColor: ['#e67e22', '#27ae60'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
