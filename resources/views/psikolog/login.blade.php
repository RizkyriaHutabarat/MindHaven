<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindHaven - Login Psikolog</title>
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
            max-width: 900px;
            padding: 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="h-screen flex flex-col">
    <!-- Login Form -->
    <div class="flex items-center justify-center flex-1">
        <div class="form-container fade-in-up">
            <!-- Alert -->
            @if(session('success'))
                <div class="mb-4 text-base text-green-700 bg-green-100 border border-green-500 rounded p-4">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-4 text-base text-red-700 bg-red-100 border border-red-500 rounded p-4">
                    {{ session('error') }}
                </div>
            @endif

            <h2 class="text-center text-gray-900 mb-8 text-2xl font-bold">Login</h2>
            <form method="POST" action="{{ route('psikolog.login') }}">
                @csrf
                <div class="mb-9">
                    <label for="email" class="block text-gray-900 font-semibold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control w-full px-4 py-3 text-lg rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your email" required>
                </div>
                <div class="mb-9">
                    <label for="password" class="block text-gray-900 font-semibold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="form-control w-full px-4 py-3 text-lg rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-800 transition duration-300 text-lg font-semibold">Login</button>
            </form>
            <p class="text-center text-gray-600 mt-6">Sudah Punya Akun? <a href="{{ route('psikolog.register') }}" class="text-green-600 hover:underline">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
