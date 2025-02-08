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

    <!-- Main Content -->
    <div class="content">
    @if (session('psikolog_token'))
    <<script>
        localStorage.setItem('psikolog_token', '{{ session('psikolog_token') }}');
    </script>
    @endif
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
        <div class="col-md-4">
            <?php 
            use App\Models\LaporanPsikolog;
            use Illuminate\Support\Facades\Auth;

            // Ambil ID psikolog yang sedang login
            $id_psikolog = Auth::user()->id;


            // Menghitung jumlah laporan berdasarkan status dan ID psikolog
            $pendingCount = LaporanPsikolog::where('id_psikolog', $id_psikolog)
                ->where('status_laporan', 'pending')->count();

            $completedCount = LaporanPsikolog::where('id_psikolog', $id_psikolog)
                ->where('status_laporan', 'selesai')->count();

            $inProgressCount = LaporanPsikolog::where('id_psikolog', $id_psikolog)
                ->where('status_laporan', 'ditolak')->count();
            ?>
            <div class="card">
                <div class="card-header text-center">
                    Laporan Pending
                </div>
                <div class="card-body text-center">
                    <h5>{{ $pendingCount }}</h5>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    Laporan Selesai
                </div>
                <div class="card-body text-center">
                    <h5>{{ $completedCount }}</h5>
                </div>
            </div>
        </div>       
    </div>

    <!-- Laporan Status Overview -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Status Laporan
                </div>
                <div class="card-body text-center">
                    <canvas id="laporanStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<<script>
    var ctx = document.getElementById('laporanStatusChart').getContext('2d');
    var laporanStatusChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Selesai'],
            datasets: [{
                label: 'Status Laporan',
                data: [@json($pendingCount), @json($completedCount)],
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


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
