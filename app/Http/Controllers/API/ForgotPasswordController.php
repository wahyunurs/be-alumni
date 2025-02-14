<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    // Step 1: Mengirim OTP untuk reset password
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            $user = User::where('email', $request->email)->first();

            // Generate OTP (6 digit)
            $otpCode = rand(100000, 999999);

            // Simpan OTP ke database dengan kedaluwarsa
            Otp::updateOrCreate(
                ['email' => $user->email],
                ['otp' => $otpCode, 'expires_at' => now()->addMinutes(15)]
            );

            // Kirim email OTP
            Mail::to($user->email)->send(new SendOtpMail($otpCode));

            return response()->json([
                'message' => 'OTP telah dikirim ke email Anda.',
                'email' => $user->email
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error sending OTP: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan, coba lagi.'], 500);
        }
    }

    // Step 2: Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6'
        ]);

        $otp = Otp::where('email', $request->email)
                  ->where('otp', $request->otp)
                  ->where('expires_at', '>=', now())
                  ->first();

        if (!$otp) {
            return response()->json(['error' => 'OTP tidak valid atau telah kadaluwarsa.'], 400);
        }

        // OTP valid
        return response()->json([
            'message' => 'OTP valid. Silakan masukkan password baru Anda.',
            'email' => $request->email
        ], 200);
    }

    // Step 3: Reset password setelah OTP diverifikasi
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            // Ubah password user
            $user->password = Hash::make($request->password);
            $user->save();

            // Hapus OTP setelah digunakan
            Otp::where('email', $request->email)->delete();

            return response()->json(['message' => 'Password berhasil direset, silahkan login.'], 200);
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mereset password, coba lagi.'], 500);
        }
    }
}
