<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindHaven - Register</title>
    <!-- Tambahkan Tailwind CSS -->
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .form-container {
            max-width: 800px;
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center">
    <!-- Register Form -->
    <div class="form-container">
        <h2 class="text-center text-gray-900 text-2xl font-bold mb-8">Register</h2>
        <form method="POST" action="/register">
            @csrf
            <div class="grid grid-cols-2 gap-6 items-center">
                <label for="nama" class="text-gray-700 font-semibold flex items-center">
                    <i class="fas fa-user mr-2 text-green-500"></i> Nama Lengkap
                </label>
                <input type="text" name="nama" id="nama" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan nama Anda" required>
                
                <label for="email" class="text-gray-700 font-semibold flex items-center">
                    <i class="fas fa-envelope mr-2 text-green-500"></i> Email
                </label>
                <input type="email" name="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan email Anda" required>
                
                <label for="password" class="text-gray-700 font-semibold flex items-center">
                    <i class="fas fa-lock mr-2 text-green-500"></i> Password
                </label>
                <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan password Anda" required>
                
                <label for="password_confirmation" class="text-gray-700 font-semibold flex items-center">
                    <i class="fas fa-lock mr-2 text-green-500"></i> Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan ulang password Anda" required>
                
                <label for="jenis_kelamin" class="text-gray-700 font-semibold flex items-center">
                    <i class="fas fa-venus-mars mr-2 text-green-500"></i> Jenis Kelamin
                </label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <option value="" disabled selected>Pilih jenis kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
                
                <label for="tanggal_lahir" class="text-gray-700 font-semibold flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i> Tanggal Lahir
                </label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                
                <label for="nomor_telepon" class="text-gray-700 font-semibold flex items-center">
                    <i class="fas fa-phone-alt mr-2 text-green-500"></i> Nomor Telepon (Opsional)
                </label>
                <input type="text" name="nomor_telepon" id="nomor_telepon" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan nomor telepon Anda">
            </div>
            <button type="submit" class="btn btn-primary w-full bg-green-600 text-white py-3 mt-6 rounded-lg hover:bg-green-800 transition duration-300">
                Daftar
            </button>
        </form>
        <p class="text-center text-gray-600 mt-4">Sudah punya akun? <a href="/login" class="text-green-600 hover:underline">Login di sini</a></p>
    </div>
</body>
</html>
