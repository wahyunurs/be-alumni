<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class SSOController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
{
    try {
        // Mendapatkan data user dari Google
        Log::info('Memulai proses SSO Google callback.');
        $googleUser = Socialite::driver('google')->stateless()->user();
        Log::info('Data user dari Google:', (array) $googleUser);

        $email = $googleUser->getEmail();
        Log::info('Email user: ' . $email);

        // Cek apakah email menggunakan domain @mhs.dinus.ac.id
        if (!str_ends_with($email, '@mhs.dinus.ac.id')) {
            Log::warning('Email tidak valid untuk SSO: ' . $email);
            return response()->json(['error' => 'Akses SSO hanya diizinkan untuk email @mhs.dinus.ac.id'], 403);
        }

        // Mencari user berdasarkan email
        $existingUser = User::where('email', $email)->first();
        Log::info('User ditemukan: ' . ($existingUser ? 'Ya' : 'Tidak'));

        if ($existingUser) {
            // Login user yang sudah ada
            Auth::login($existingUser);
        } else {
            // Buat user baru
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $email,
                'password' => bcrypt(\Str::random(24)), 
                'role' => 'mahasiswa', 
            ]);
            Log::info('User baru dibuat: ' . $newUser->id);

            // Tandai email sudah diverifikasi
            $newUser->email_verified_at = now();
            $newUser->save();
            Log::info('Email diverifikasi: ' . $newUser->email);

            // Assign role "mahasiswa"
            $newUser->assignRole('mahasiswa');
            Auth::login($newUser);
        }

        // Buat token JWT
        $token = JWTAuth::fromUser(Auth::user());
        Log::info('Token JWT berhasil dibuat.');

        // URL frontend 
        // $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000/api/auth/google');
        $frontendUrl = env('FRONTEND_URL', 'https://sti.dinus.ac.id/Alumni/api/auth/google');
        Log::info('Frontend URL: ' . $frontendUrl);

        // Ambil user dari token
        $user = JWTAuth::user();

        // Ambil peran (roles) pengguna
        $roles = $user->getRoleNames();
        
        // Data user
        $queryParams = http_build_query([
            'id' => $user->id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->role, 
            ],
            'token' => $token,
        ]);

        $redirectUrl = $frontendUrl . '?' . $queryParams;
        Log::info('Redirect URL: ' . $redirectUrl);

        return redirect()->away($redirectUrl);

    } catch (Exception $e) {
        // Log error
        Log::error('Error saat login SSO: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to handle SSO callback.'], 500);
    }
}

}




    
//     public function SendTokenToFrontend()
// {
//     try {
//         // Ambil user yang sedang login
//         $user = auth()->user();

//         // Pastikan user sudah login
//         if (!$user) {
//             return response()->json([
//                 'error' => 'User tidak ditemukan atau belum login.'
//             ], 401);
//         }

//         // Encode informasi user untuk dikirim ke FE
//         $userData = [
//             'id' => $user->id,
//             'name' => $user->name,
//             'email' => $user->email,
//             'roles' => $user->getRoleNames(),
//         ];

//         return response()->json([
//             "data" => $userData
//         ]);
        
//     } catch (\Exception $e) {
//         // Log error jika terjadi kesalahan
//         Log::error('Gagal mengirim token ke FE: ' . $e->getMessage());

//         return response()->json([
//             'error' => 'Terjadi kesalahan saat mengirim token ke frontend.'
//         ], 500);
//     }
// }


