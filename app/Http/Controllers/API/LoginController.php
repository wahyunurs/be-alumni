<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    // Memproses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mengambil kredensial
        $credentials = $request->only('email', 'password');

        try {
            // Cek apakah kredensial cocok dan token dapat dibuat
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Email atau password salah.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Gagal membuat token: ' . $e->getMessage()], 500);
        }

        // Ambil user dari token
        $user = JWTAuth::user();

        // Ambil peran (roles) pengguna
        $roles = $user->getRoleNames();

        // Kembalikan token, informasi pengguna, dan waktu kadaluwarsa token
        return response()->json([
            'message' => 'Login berhasil!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(), 
            ],
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 1440, // Waktu dalam detik
        ], 200);
    }

    // Memproses logout
    public function logout(Request $request)
    {
        try {
            // Cek apakah ada token
            if (!$token = JWTAuth::getToken()) {
                return response()->json(['error' => 'Token tidak ditemukan.'], 400);
            }
            
            // Invalidate token
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Anda telah logout.'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Gagal melakukan logout, coba lagi: ' . $e->getMessage()], 500);
        }
    }

    // Mendapatkan informasi user yang sedang login
    public function getUser(Request $request)
    {
        // Ambil user dari token
        $user = JWTAuth::user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ]);
    }

    // // Memproses refresh token
    // public function refreshToken(Request $request)
    // {
    //     try {
    //         // Refresh token yang ada
    //         $newToken = JWTAuth::refresh(JWTAuth::getToken());
    //         return response()->json([
    //             'token' => $newToken,
    //             'message' => 'Token has been refreshed.',
    //             'expires_in' => JWTAuth::factory()->getTTL() * 60, // Waktu dalam detik
    //         ], 200);
    //     } catch (JWTException $e) {
    //         return response()->json(['error' => 'Failed to refresh token: ' . $e->getMessage()], 500);
    //     }
    // }
}
