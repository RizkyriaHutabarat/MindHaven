<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Edit User</title>
    <link rel="icon" href="{{ asset('assets/images/logo2.png') }}" type="image/png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">

    <!-- Edit User Form -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-2xl">
            <!-- Form Header -->
            <h2 class="text-center text-3xl font-semibold text-gray-800 mb-6">Edit User</h2>

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

            <!-- Form to edit user -->
            <form action="{{ route('admin.update_user', $user->id_user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name Input -->
                    <div class="mb-4">
                        <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                        @error('nama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender Input -->
                    <div class="mb-4">
                        <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">Jenis Kelamin</label>
                        <input type="text" id="jenis_kelamin" name="jenis_kelamin" value="{{ old('jenis_kelamin', $user->jenis_kelamin) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Date Input -->
                    <div class="mb-4">
                        <label for="tanggal_lahir" class="block text-gray-700 font-semibold mb-2">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number Input -->
                    <div class="mb-4">
                        <label for="nomor_telepon" class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        @error('nomor_telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Update User
                    </button>
                </div>
            </form>

            <!-- Back to User Management -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.manage_users') }}" class="text-green-600 hover:underline">Back to User Management</a>
            </div>
        </div>
    </div>

</body>
</html>
