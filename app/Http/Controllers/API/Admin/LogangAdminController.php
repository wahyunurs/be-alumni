<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Logang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LogangAdminController extends Controller
{
    public function indexadmin(Request $request)
    {
        Log::info('Fetching Logang data with filters.', $request->all());

        $today = Carbon::today()->toDateString();
        $selectedTipeMagang = $request->input('TipeMagang');
        $selectedPengalaman = $request->input('Pengalaman');
        $selectedTags = $request->input('tags');

        $query = Logang::where('MasaBerlaku', '>=', $today)
;

        if ($selectedTipeMagang) {
            $query->where('TipeMagang', 'LIKE', '%' . $selectedTipeMagang . '%');
        }
        if ($selectedPengalaman) {
            $query->where('Pengalaman', 'LIKE', '%' . $selectedPengalaman . '%');
        }
        if ($selectedTags) {
            $query->where('Tags', 'LIKE', '%' . $selectedTags . '%');
        }

        $logangAdmin = $query->orderBy('updated_at', 'desc')->get();

        if ($logangAdmin->isEmpty()) {
            Log::info('No Logang data found for given filters.', $request->all());
            return response()->json(['message' => 'Logang not found'], 404);
        }

        Log::info('Logang data fetched successfully.', ['count' => $logangAdmin->count()]);
        
        $tipeMagangCounts = [
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
            'message' => $logangAdmin->isEmpty() ? 'Logang not found' : null,
            'data' => $logangAdmin->isEmpty() ? [] : $logangAdmin,
            'filters' => [
                'selectedTipeMagang' => $selectedTipeMagang ?: null,
                'selectedPengalaman' => $selectedPengalaman ?: null,
                'selectedTags' => $selectedTags ?: null,
            ],
            'counts' => [
                'TipeMagang' => $tipeMagangCounts,
                'Pengalaman' => $pengalamanCounts,
            ],
        ], 200);
    }

    public function show($id)
    {
        Log::info("Fetching Logang data for ID: $id");

        $logang = Logang::find($id);

        if (!$logang) {
            Log::error("Logang data not found for ID: $id");
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        Log::info("Logang data fetched successfully for ID: $id");
        return response()->json(['success' => true, 'data' => $logang], 200);
    }

    public function verify(Request $request, $id)
    {
        Log::info("Updating verification status for Logang ID: $id", $request->all());

        $logangAdmin = Logang::find($id);

        if (!$logangAdmin) {
            Log::error("Logang not found for ID: $id");
            return response()->json(['status' => 'error', 'message' => 'Lowongan not found'], 404);
        }

        if ($request->input('action') == 'verify') {
            $logangAdmin->Verify = 'verified';
        } elseif ($request->input('action') == 'not_verify') {
            $logangAdmin->Verify = 'pending';
        }

        $logangAdmin->save();

        Log::info("Verification status updated successfully for Logang ID: $id", ['status' => $logangAdmin->Verify]);

        return response()->json(['status' => 'success', 'message' => 'Lowongan status updated successfully', 'data' => $logangAdmin], 200);
    }

    public function destroy($id)
    {
        Log::info("Deleting Logang for ID: $id");

        $logangAdmin = Logang::find($id);

        if (!$logangAdmin) {
            Log::error("Logang not found for ID: $id");
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        $logangAdmin->delete();

        Log::info("Logang deleted successfully for ID: $id");
        return response()->json(['success' => true, 'message' => 'Lowongan deleted successfully'], 200);
    }

    public function store(Request $request)
    {
        Log::info('Creating new Logang', $request->all());

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
                'Verify' => 'verified',
            ]);

            Log::info('Logang created successfully.', ['logang_id' => $logang->id]);

            return response()->json(['message' => 'Logang created successfully', 'logang' => $logang], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create Logang.', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to create Logang', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info("Request received to update Logang", ['id' => $id, 'request_data' => $request->all()]);
    
        try {
            $user = auth()->user();
    
            if (!$user) {
                Log::error("Unauthorized access attempt for updating Logang", ['id' => $id]);
               
                return response()->json(['status' => 'error', 'message' => 'Unauthorized or user does not have access'], 403);           
            }
    
            $validated = $request->validate([
                'NamaPerusahaan' => 'nullable|string|max:255',
                'Posisi' => 'nullable|string|max:255',
                'Alamat' => 'nullable|string|max:255',
                'Pengalaman' => 'nullable|string|max:255',
                'Gaji' => 'nullable|string|max:255',
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
    
            Log::info("Validation passed for updating Logang", ['id' => $id, 'validated_data' => $validated]);
    
            $filename = null;
    
            if ($request->hasFile('Logo')) {
                $Logo = $request->file('Logo');
                $filename = date('Y-m-d') . $Logo->getClientOriginalName();
                $path = '/imglogo/' . $filename;
    
                Storage::disk('public')->put($path, file_get_contents($Logo));
                Log::info("New logo uploaded", ['filename' => $filename]);
            } else {
                $filename = $request->input('existingLogo');
                Log::info("Using existing logo", ['filename' => $filename]);
            }
    
            $logang = Logang::findOrFail($id);
    
            Log::info("Logang found for update", ['id' => $id]);
    
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
                'Verify' => 'verified'
            ]);
    
            Log::info("Logang successfully updated", ['id' => $id]);
    
            return response()->json(['status' => 'success', 'message' => 'Lowongan updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Failed to update Logang", ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error','message' => 'Failed to update data','error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Manage Logang Admin.
     */
    public function manage(Request $request)
    {
        Log::info("Fetching all Logang data for admin management");
    
         $logangAdmin = Logang::latest()->get();
    
        if ($logangAdmin->isEmpty()) {
            Log::warning("No Logang data found");
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $logangAdmin], 200);
    }
}
