<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerificationController extends Controller
{
    public function verifyOtp(Request $request)
    {
        // Validasi hanya OTP
        $request->validate([
            'otp' => 'required|string',
        ]);

        // Cek apakah OTP valid dan ambil pengguna terkait
        $otp = Otp::where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json(['error' => 'OTP salah atau sudah kadaluwarsa.'], 400);
        }

        // Dapatkan pengguna berdasarkan email dari OTP
        $user = User::where('email', $otp->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Tandai email pengguna sebagai verified
        $user->email_verified_at = now();
        $user->save();

        // Hapus OTP setelah berhasil diverifikasi
        $otp->delete();

        // Login user dengan JWT
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Email berhasil diverifikasi, silahkan login.',
        ], 200);
    }
}
