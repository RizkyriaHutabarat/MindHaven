<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\Psikolog;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'nama' => 'required|max:100',
            'password' => 'required|min:6|confirmed',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        // failed handling if validation failed


        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tipe_user' => 'klien',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'The provided credentials are incorrect.'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function loginAdmin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Cek kredensial admin
            $admin = Admin::where('email', $request->email)->first();

            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return response()->json([
                    'message' => 'Invalid admin credentials'
                ], 401);
            }

            // Hapus token lama jika ada
            $admin->tokens()->delete();

            // Buat token baru
            $token = $admin->createToken('admin_token', ['admin'])->plainTextToken;

            return response()->json([
                'message' => 'Admin login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'admin' => $admin
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function registerPsikolog(Request $request)
{
    try {
        // Validasi request
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:psikologs',
            'password' => 'required|string|min:6|confirmed',
            'spesialisasi' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            try {
                $fotoPath = $request->file('foto')->store('uploads/psikolog', 'public');
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error uploading photo',
                    'error' => $e->getMessage()
                ], 422);
            }
        }

        // Buat record psikolog baru
        $psikolog = Psikolog::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'spesialisasi' => $request->spesialisasi,
            'bio' => $request->bio,
            'foto' => $fotoPath,
            'status' => 'inactive', // Default status
        ]);

        // Hapus token lama jika ada
        $psikolog->tokens()->delete();

        // Generate token baru
        $token = $psikolog->createToken('psikolog_token', ['psikolog'])->plainTextToken;

        // Response sukses
        return response()->json([
            'message' => 'Psikolog registration successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'psikolog' => $psikolog
        ], 201);

    } catch (\Exception $e) {
        // Jika foto sudah terupload tapi create user gagal, hapus foto
        if (isset($fotoPath)) {
            Storage::disk('public')->delete($fotoPath);
        }

        return response()->json([
            'message' => 'Registration failed',
            'error' => $e->getMessage()
        ], 400);
    }
}

public function loginPsikolog(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek kredensial psikolog
        $psikolog = Psikolog::where('email', $request->email)->first();

        if (!$psikolog || !Hash::check($request->password, $psikolog->password)) {
            return response()->json([
                'message' => 'Invalid psikolog credentials'
            ], 401);
        }

        // Hapus token lama
        $psikolog->tokens()->delete();

        // Buat token baru
        $token = $psikolog->createToken('psikolog_token', ['psikolog'])->plainTextToken;

        return response()->json([
            'message' => 'Psikolog login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'psikolog' => $psikolog
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
}


}

