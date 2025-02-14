<?php

namespace App\Http\Controllers\API\Mahasiswa\CVMhs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Interest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InterestMhsController extends Controller
{
    /**
     * Get the logged-in user's interests
     */
    public function index()
    {
        try {
            // Get logged-in user's ID
            $userId = Auth::id();

            // Retrieve user's interests
            $interests = Interest::whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();

            if ($interests->isEmpty()) {
                Log::info("No interests found for user ID: $userId");

                return response()->json([
                    'success' => true,
                    'message' => 'No interests found for the user.',
                    'data' => [],
                ], 200);
            }

            Log::info("Interests retrieved successfully for user ID: $userId");

            return response()->json([
                'success' => true,
                'data' => $interests,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error retrieving interests: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve interests.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store or update the logged-in user's interests
     */
    public function storeOrUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'interests' => 'required|array',
                'interests.*' => 'string|max:255',
            ]);

            // Get logged-in user's ID
            $userId = Auth::id();

            // Find or create interests and attach them to the user
            $interestIds = collect($validated['interests'])->map(function ($interestName) {
                return Interest::firstOrCreate(['name' => $interestName])->id;
            })->toArray();

            // Sync user interests through the pivot table
            $user = Auth::user();
            $user->interests()->sync($interestIds);

            Log::info("Interests updated successfully for user ID: $userId");

            return response()->json([
                'success' => true,
                'message' => 'Interests updated successfully.',
                'data' => $user->interests,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error updating interests: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update interests.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
