<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Edit Psikolog</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">

    <!-- Edit Psikolog Form -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-2xl">
            <!-- Form Header -->
            <h2 class="text-center text-3xl font-semibold text-gray-800 mb-6">Edit Psikolog</h2>

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

            <!-- Form to edit psikolog -->
            <form action="{{ route('admin.update_psikolog', $psikolog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name Input -->
                    <div class="mb-4">
                        <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $psikolog->nama) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $psikolog->email) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                    </div>

                    <!-- Specialization Input -->
                    <div class="mb-4">
                        <label for="spesialisasi" class="block text-gray-700 font-semibold mb-2">Spesialisasi</label>
                        <input type="text" id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi', $psikolog->spesialisasi) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Bio Input -->
                    <div class="mb-4">
                        <label for="bio" class="block text-gray-700 font-semibold mb-2">Bio</label>
                        <textarea id="bio" name="bio" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ old('bio', $psikolog->bio) }}</textarea>
                    </div>

                    <!-- Photo Input -->
                    <div class="mb-4">
                        <label for="foto" class="block text-gray-700 font-semibold mb-2">Foto</label>
                        <input type="file" id="foto" name="foto" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        @if($psikolog->foto)
                            <div class="mt-4">
                                <img src="{{ asset('storage/' . $psikolog->foto) }}" alt="Foto Psikolog" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Save Changes
                    </button>
                </div>
            </form>

            <!-- Back to Psikolog Management -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.manage_psikologs') }}" class="text-green-600 hover:underline">Back to Psikolog Management</a>
            </div>
        </div>
    </div>

</body>
</html>
