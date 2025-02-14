<?php

namespace App\Http\Controllers\API\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Loker;
use App\Notifications\LokerCreatedNotifications;
use App\Notifications\LokerEditedNotifications;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Notification;
use Storage;

class LokerMitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexmitra(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $selectedTipeKerja = $request->input('TipeKerja'); 
        $selectedPengalaman = $request->input('Pengalaman');
        $selectedTags = $request->input('tags');
        
        $query = Loker::where('MasaBerlaku', '>=', $today)
            ->where('Verify', '=', 'verified');

        if ($selectedTipeKerja) {
            $query->where('TipeKerja', 'LIKE', '%' . $selectedTipeKerja . '%');
        }
        if ($selectedPengalaman) {
            $query->where('Pengalaman', 'LIKE', '%' . $selectedPengalaman . '%');
        }
        if ($selectedTags) {
            $query->where('Tags', 'LIKE', '%' . $selectedTags . '%');
        }

        $lokers = $query->orderBy('updated_at', 'desc')->get();

        Log::info('Lokers retrieved successfully.', ['count' => $lokers->count()]);

        $TipeKerjaCounts = [
            'freelance' => Loker::where('TipeKerja', 'Freelance')->count(),
            'full time' => Loker::where('TipeKerja', 'Full Time')->count(),
            'part time' => Loker::where('TipeKerja', 'Part Time')->count(),
            'kontrak' => Loker::where('TipeKerja', 'Kontrak')->count(),
            'sementara' => Loker::where('TipeKerja', 'Sementara')->count(),
        ];

        $pengalamanCounts = [
            'tanpa pengalaman' => Loker::where('Pengalaman', 'Tanpa Pengalaman')->count(),
            'fresh graduate' => Loker::where('Pengalaman', 'Fresh Graduate')->count(),
            'minimal 1 tahun' => Loker::where('Pengalaman', 'Minimal 1 Tahun')->count(),
            'minimal 2 tahun' => Loker::where('Pengalaman', 'Minimal 2 Tahun')->count(),
            'minimal 3 tahun' => Loker::where('Pengalaman', 'Minimal 3 Tahun')->count(),
            'lebih dari 3 tahun' => Loker::where('Pengalaman', 'Lebih dari 3 Tahun')->count(),
        ];

        return response()->json([
            'message' => $lokers->isEmpty() ? 'Loker not found' : null,
            'data' => $lokers->isEmpty() ? [] : $lokers,
            'filters' => [
                'selectedTipeKerja' => $selectedTipeKerja ?: null,
                'selectedPengalaman' => $selectedPengalaman ?: null,
                'selectedTags' => $selectedTags ?: null,
            ],
            'counts' => [
                'TipeKerja' => $TipeKerjaCounts,
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
                'TipeKerja' => 'required|string|max:255',
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
                Log::info('Unauthorized user attempted to create a loker.');
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

            $loker = Loker::create([
                'user_id' => $user->id,
                'NamaPerusahaan' => $validated['NamaPerusahaan'],
                'Posisi' => $validated['Posisi'],
                'Alamat' => $validated['Alamat'],
                'Pengalaman' => $validated['Pengalaman'],
                'Gaji' => $validated['Gaji'] ?? null,
                'TipeKerja' => $validated['TipeKerja'],
                'Deskripsi' => $validated['Deskripsi'],
                'Website' => $validated['Website'] ?? null,
                'Email' => $validated['Email'],
                'no_hp' => $validated['no_hp'] ?? null,
                'Tags' => $validated['Tags'] ?? null,
                'MasaBerlaku' => $validated['MasaBerlaku'],
                'Logo' => $filename,
                'Verify' => 'verified'
            ]);

            Log::info('Loker created successfully.', ['loker_id' => $loker->id]);
            return response()->json(['message' => 'Loker created successfully', 'loker' => $loker], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create loker.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create Loker', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
        $loker = Loker::findOrFail($id);
        Log::info('Loker retrieved successfully.', ['loker_id' => $loker->id]);
        return response()->json($loker);
            
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user(); 

            if (!$user) {
                Log::info('Unauthorized user attempted to update a loker.', ['loker_id' => $id]);
                return response()->json(['status' => 'error', 'message' => 'Unauthorized or user does not have access'], 403);
            }

            $validated = $request->validate([
                'NamaPerusahaan' => 'nullable|string|max:255',
                'Posisi' => 'nullable|string|max:255',
                'Alamat' => 'nullable|string|max:255',
                'Pengalaman' => 'nullable|string|min:0',
                'Gaji' => 'nullable|string|min:0',
                'TipeKerja' => 'nullable|string|max:50',
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

            $loker = Loker::findOrFail($id);            
            $loker->update([
                'user_id' => $user->id,
                'NamaPerusahaan' => $request->filled('NamaPerusahaan') ? $request->NamaPerusahaan : $loker->NamaPerusahaan,
                'Posisi' => $request->filled('Posisi') ? $request->Posisi : $loker->Posisi,
                'Alamat' => $request->filled('Alamat') ? $request->Alamat : $loker->Alamat,
                'Pengalaman' => $request->filled('Pengalaman') ? $request->Pengalaman : $loker->Pengalaman,
                'Gaji' => $request->filled('Gaji') ? $request->Gaji : $loker->Gaji,
                'TipeKerja' => $request->filled('TipeKerja') ? $request->TipeKerja : $loker->TipeKerja,
                'Deskripsi' => $request->filled('Deskripsi') ? $request->Deskripsi : $loker->Deskripsi,
                'Website' => $request->filled('Website') ? $request->Website : $loker->Website,
                'Email' => $request->filled('Email') ? $request->Email : $loker->Email,
                'no_hp' => $request->filled('no_hp') ? $request->no_hp : $loker->no_hp,
                'Tags' => $request->filled('Tags') ? $request->Tags : $loker->Tags,
                'MasaBerlaku' => $request->filled('MasaBerlaku') ? $request->MasaBerlaku : $loker->MasaBerlaku,
                'Logo' => $filename ?: $loker->Logo,
                'Verify' => 'verified'
            ]);

            Log::info('Loker updated successfully.', ['loker_id' => $loker->id]);
            return response()->json(['status' => 'success', 'message' => 'Loker updated successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Failed to update loker.', ['loker_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to update loker', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $loker = Loker::find($id);

        if (!$loker) {
            Log::info('Loker not found for deletion.', ['loker_id' => $id]);
            return response()->json(['message' => 'Loker not found'], 404);
        }

        $loker->delete();
        Log::info('Loker deleted successfully.', ['loker_id' => $id]);

        return response()->json(['message' => 'Loker successfully deleted']);
    }

    public function manage(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();

        $loker = Loker::where('user_id', $user->id)
            ->where('MasaBerlaku', '>=', $today)
            ->latest()
            ->get();

        Log::info('Loker manage list retrieved.', ['user_id' => $user->id, 'count' => $loker->count()]);

        return response()->json($loker->isEmpty() ? "Loker not found" : $loker);    
    }
}
