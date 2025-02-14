<?php

namespace App\Http\Controllers\API\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Logang;
use App\Notifications\LogangCreatedNotifications;
use App\Notifications\LogangEditedNotifications;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Notification;
use Storage;

class LogangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $selectedTipeMagang = $request->input('TipeMagang'); 
        $selectedPengalaman = $request->input('Pengalaman');
        $selectedTags = $request->input('tags');
        
        $query = Logang::where('MasaBerlaku', '>=', $today)
            ->where('Verify', '=', 'verified');

        if ($selectedTipeMagang) {
            $query->where('TipeMagang', 'LIKE', '%' . $selectedTipeMagang . '%');
        }
        if ($selectedPengalaman) {
            $query->where('Pengalaman', 'LIKE', '%' . $selectedPengalaman . '%');
        }
        if ($selectedTags) {
            $query->where('Tags', 'LIKE', '%' . $selectedTags . '%');
        }

        $logangs = $query->orderBy('updated_at', 'desc')->get();

        Log::info('Logangs retrieved successfully.', ['count' => $logangs->count()]);

        $TipeMagangCounts = [
            'freelance' => Logang::where('TipeMagang', 'Freelance')->count(),
            'full time' => Logang::where('TipeMagang', 'Full Time')->count(),
            'part time' => Logang::where('TipeMagang', 'Part Time')->count(),
            'kontrak' => Logang::where('TipeMagang', 'Kontrak')->count(),
            'sementara' => Logang::where('TipeMagang', 'Sementara')->count(),
        ];

        $pengalamanCounts = [
            'tanpa pengalaman' => Logang::where('Pengalaman', 'Tanpa Pengalaman')->count(),
            'fresh graduate' => Logang::where('Pengalaman', 'Fresh Graduate')->count(),
            'minimal 1 tahun' => Logang::where('Pengalaman', 'Minimal 1 Tahun')->count(),
            'minimal 2 tahun' => Logang::where('Pengalaman', 'Minimal 2 Tahun')->count(),
            'minimal 3 tahun' => Logang::where('Pengalaman', 'Minimal 3 Tahun')->count(),
            'lebih dari 3 tahun' => Logang::where('Pengalaman', 'Lebih dari 3 Tahun')->count(),
        ];

        return response()->json([
            'message' => $logangs->isEmpty() ? 'Logang not found' : null,
            'data' => $logangs->isEmpty() ? [] : $logangs,
            'filters' => [
                'selectedTipeMagang' => $selectedTipeMagang ?: null,
                'selectedPengalaman' => $selectedPengalaman ?: null,
                'selectedTags' => $selectedTags ?: null,
            ],
            'counts' => [
                'TipeMagang' => $TipeMagangCounts,
                'Pengalaman' => $pengalamanCounts,
            ],
        ]);   
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'NamaPerusahaan' => 'required|string|max:255',
                'Posisi' => 'required|string|max:255',
                'Alamat' => 'required|string|max:255',
                'Pengalaman' => 'required|string|max:255',
                'Gaji' => 'nullable|string|max:255',
                'TipeMagang' => 'required|string|max:255',
                'Deskripsi' => 'required|string',
                'Website' => 'nullable|url',
                'Email' => 'required|email',
                'no_hp' => 'nullable|string|max:15',
                'Tags' => 'required|string',
                'MasaBerlaku' => 'required|date',
                'Logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = auth()->user(); 
            if (!$user) {
                Log::info('Unauthorized user attempted to create a logang.');
                return response()->json(['status' => 'error', 'message' => 'Unauthorized or user does not have access'], 403);
            }       

            if ($request->hasFile('Logo')) {
                $image = $request->file('Logo');
                $filename = date('Y-m-d') . $image->getClientOriginalName();
                $path = '/imglogo/' . $filename;
                Storage::disk('public')->put($path, file_get_contents($image));
            } else {
                $filename = 'default_logo.png'; 
            }

            $logang = Logang::create([
                'user_id' => $user->id,
                'NamaPerusahaan' => $validated['NamaPerusahaan'],
                'Posisi' => $validated['Posisi'],
                'Alamat' => $validated['Alamat'],
                'Pengalaman' => $validated['Pengalaman'],
                'Gaji' => $validated['Gaji'] ?? null,
                'TipeMagang' => $validated['TipeMagang'],
                'Deskripsi' => $validated['Deskripsi'],
                'Website' => $validated['Website'] ?? null,
                'Email' => $validated['Email'],
                'no_hp' => $validated['no_hp'] ?? null,
                'Tags' => $validated['Tags'] ?? null,
                'MasaBerlaku' => $validated['MasaBerlaku'],
                'Logo' => $filename,
                'Verify' => 'pending'
            ]);

            $adminEmail = 'wise@dsn.dinus.ac.id'; // Email admin
            Notification::route('mail', $adminEmail)->notify(new LogangCreatedNotifications($logang));

            Log::info('Logang created successfully.', ['logang_id' => $logang->id]);
            return response()->json(['message' => 'Logang created successfully', 'logang' => $logang], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create logang.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create Logang', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
        $logang = Logang::findOrFail($id);
        Log::info('Logang retrieved successfully.', ['logang_id' => $logang->id]);
        return response()->json($logang);
            
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user(); 

            if (!$user) {
                Log::info('Unauthorized user attempted to update a logang.', ['logang_id' => $id]);
                return response()->json(['status' => 'error', 'message' => 'Unauthorized or user does not have access'], 403);
            }

            $validated = $request->validate([
                'NamaPerusahaan' => 'nullable|string|max:255',
                'Posisi' => 'nullable|string|max:255',
                'Alamat' => 'nullable|string|max:255',
                'Pengalaman' => 'nullable|string|min:0',
                'Gaji' => 'nullable|string|min:0',
                'TipeMagang' => 'nullable|string|max:50',
                'Deskripsi' => 'nullable|string',
                'Website' => 'nullable|url',
                'Email' => 'nullable|email',
                'no_hp' => 'nullable|string|max:15',
                'Tags' => 'nullable|string',
                'MasaBerlaku' => 'nullable|date',
                'Logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'existingLogo' => 'nullable|string'
            ]);

            $filename = null;

            if ($request->hasFile('Logo')) {
                $Logo = $request->file('Logo');
                $filename = date('Y-m-d') . $Logo->getClientOriginalName();
                $path = '/imglogo/' . $filename;

                Storage::disk('public')->put($path, file_get_contents($Logo));
            } else {
                $filename = $request->input('existingLogo');
            }

            $logang = Logang::findOrFail($id);            
            $logang->update([
                'user_id' => $user->id,
                'NamaPerusahaan' => $request->filled('NamaPerusahaan') ? $request->NamaPerusahaan : $logang->NamaPerusahaan,
                'Posisi' => $request->filled('Posisi') ? $request->Posisi : $logang->Posisi,
                'Alamat' => $request->filled('Alamat') ? $request->Alamat : $logang->Alamat,
                'Pengalaman' => $request->filled('Pengalaman') ? $request->Pengalaman : $logang->Pengalaman,
                'Gaji' => $request->filled('Gaji') ? $request->Gaji : $logang->Gaji,
                'TipeMagang' => $request->filled('TipeMagang') ? $request->TipeMagang : $logang->TipeMagang,
                'Deskripsi' => $request->filled('Deskripsi') ? $request->Deskripsi : $logang->Deskripsi,
                'Website' => $request->filled('Website') ? $request->Website : $logang->Website,
                'Email' => $request->filled('Email') ? $request->Email : $logang->Email,
                'no_hp' => $request->filled('no_hp') ? $request->no_hp : $logang->no_hp,
                'Tags' => $request->filled('Tags') ? $request->Tags : $logang->Tags,
                'MasaBerlaku' => $request->filled('MasaBerlaku') ? $request->MasaBerlaku : $logang->MasaBerlaku,
                'Logo' => $filename ?: $logang->Logo,
                'Verify' => 'pending'
            ]);

            $adminEmail = 'wise@dsn.dinus.ac.id'; // Email admin
            Notification::route('mail', $adminEmail)->notify(new LogangEditedNotifications($logang));

            Log::info('Logang updated successfully.', ['logang_id' => $logang->id]);
            return response()->json(['status' => 'success', 'message' => 'Logang updated successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Failed to update logang.', ['logang_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to update logang', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $logang = Logang::find($id);

        if (!$logang) {
            Log::info('Logang not found for deletion.', ['logang_id' => $id]);
            return response()->json(['message' => 'Logang not found'], 404);
        }

        $logang->delete();
        Log::info('Logang deleted successfully.', ['logang_id' => $id]);

        return response()->json(['message' => 'Logang successfully deleted']);
    }

    public function manage(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();

        $logang = Logang::where('user_id', $user->id)
            ->where('MasaBerlaku', '>=', $today)
            ->latest()
            ->get();

        Log::info('Logang manage list retrieved.', ['user_id' => $user->id, 'count' => $logang->count()]);

        return response()->json($logang->isEmpty() ? "Logang not found" : $logang);    
    }
}
