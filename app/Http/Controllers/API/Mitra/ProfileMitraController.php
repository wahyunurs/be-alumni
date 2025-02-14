<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;

class ProfileMitraController extends Controller
{
    /**
     * Display the authenticated Mitra's profile.
     */
    public function index()
    {
        // Memeriksa apakah pengguna sudah terautentikasi
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated user',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => $user->foto_profil, // Pastikan kolom ini ada di tabel users
                'roles' => $user->getRoleNames(), // Mengambil role pengguna
            ],
        ], 200);
    }

    /**
     * Store a newly created profile picture.
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'foto_profil.required' => 'Please upload a profile picture.',
            'foto_profil.image' => 'The file must be an image.',
            'foto_profil.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg.',
            'foto_profil.max' => 'The profile picture size must not exceed 2MB.',
        ]);

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/foto_profils', $filename);

            // Simpan nama file ke database di tabel users
            $Mitra = Auth::user();
            $Mitra->foto_profil = $filename; // Pastikan kolom ini ada di tabel users
            $Mitra->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile picture uploaded successfully!',
                'data' => [
                    'foto_profil' => $filename,
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No profile picture found!',
        ], 400);
    }

    /**
     * Update the authenticated Mitra's password.
     */
    public function updatePassword(Request $request)
    {
        // Validate input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'The current password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);


        // Check if the old password matches
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password does not match.',
            ], 400);
        }

        // Change password
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully.',
        ], 200);
    }
}
