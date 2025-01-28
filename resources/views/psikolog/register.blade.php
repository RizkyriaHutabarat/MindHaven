<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Psikolog - MindHaven</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <!-- Tambahkan Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;family=Playfair+Display:wght@700&amp;display=swap" rel="stylesheet"/>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fadeInUp 1s ease-out;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .form-container {
            max-width: 700px; /* Lebar form diperbesar */
            background: #ffffff;
            padding: 80px; /* Tinggi padding diperbesar */
            border-radius: 15px; /* Sudut lebih bulat */
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="h-screen flex flex-col">
    <!-- Register Form -->
    <div class="flex items-center justify-center flex-1 mt-12"> <!-- Tambahkan margin-top -->
        <div class="form-container fade-in-up">
            <!-- Alert -->
            @if(session('success'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-500 rounded p-4">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-500 rounded p-4">
                    {{ session('error') }}
                </div>
            @endif

            <h2 class="text-center text-gray-900 text-2xl font-bold mb-8">Registrasi Psikolog</h2>
            <form method="POST" action="{{ route('psikolog.register.post') }}" enctype="multipart/form-data">
                @csrf
                <!-- Nama -->
                <div class="mb-4">
                    <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan nama Anda" value="{{ old('nama') }}" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="form-control w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="form-control w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan password Anda" required>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan ulang password Anda" required>
                </div>

                <!-- Spesialisasi -->
                <div class="mb-4">
                    <label for="spesialisasi" class="block text-gray-700 font-semibold mb-2">Spesialisasi</label>
                    <input type="text" name="spesialisasi" id="spesialisasi" class="form-control w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan spesialisasi Anda" value="{{ old('spesialisasi') }}">
                </div>

                <!-- Bio -->
                <div class="mb-4">
                    <label for="bio" class="block text-gray-700 font-semibold mb-2">Bio</label>
                    <textarea name="bio" id="bio" class="form-control w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan bio Anda">{{ old('bio') }}</textarea>
                </div>

                <!-- Foto -->
                <div class="mb-4">
                    <label for="foto" class="block text-gray-700 font-semibold mb-2">Foto (Opsional)</label>
                    <input type="file" name="foto" id="foto" class="form-control w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <button type="submit" class="btn btn-primary w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-800 transition duration-300">Daftar</button>
            </form>

            <p class="text-center text-gray-600 mt-4">Sudah punya akun? <a href="{{ route('psikolog.login') }}" class="text-green-600 hover:underline">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
