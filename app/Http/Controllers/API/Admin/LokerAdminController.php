<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Loker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LokerAdminController extends Controller
{
    public function indexadmin(Request $request)
    {
        Log::info('Fetching Loker data with filters.', $request->all());

        $today = Carbon::today()->toDateString();
        $selectedTipeKerja = $request->input('TipeKerja');
        $selectedPengalaman = $request->input('Pengalaman');
        $selectedTags = $request->input('tags');

        $query = Loker::where('MasaBerlaku', '>=', $today)
;

        if ($selectedTipeKerja) {
            $query->where('TipeKerja', 'LIKE', '%' . $selectedTipeKerja . '%');
        }
        if ($selectedPengalaman) {
            $query->where('Pengalaman', 'LIKE', '%' . $selectedPengalaman . '%');
        }
        if ($selectedTags) {
            $query->where('Tags', 'LIKE', '%' . $selectedTags . '%');
        }

        $lokerAdmin = $query->orderBy('updated_at', 'desc')->get();

        if ($lokerAdmin->isEmpty()) {
            Log::info('No Loker data found for given filters.', $request->all());
            return response()->json(['message' => 'Loker not found'], 404);
        }

        Log::info('Loker data fetched successfully.', ['count' => $lokerAdmin->count()]);
        
        $tipeKerjaCounts = [
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
            'message' => $lokerAdmin->isEmpty() ? 'Loker not found' : null,
            'data' => $lokerAdmin->isEmpty() ? [] : $lokerAdmin,
            'filters' => [
                'selectedTipeKerja' => $selectedTipeKerja ?: null,
                'selectedPengalaman' => $selectedPengalaman ?: null,
                'selectedTags' => $selectedTags ?: null,
            ],
            'counts' => [
                'TipeKerja' => $tipeKerjaCounts,
                'Pengalaman' => $pengalamanCounts,
            ],
        ], 200);
    }

    public function show($id)
    {
        Log::info("Fetching Loker data for ID: $id");

        $loker = Loker::find($id);

        if (!$loker) {
            Log::error("Loker data not found for ID: $id");
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        Log::info("Loker data fetched successfully for ID: $id");
        return response()->json(['success' => true, 'data' => $loker], 200);
    }

    public function verify(Request $request, $id)
    {
        Log::info("Updating verification status for Loker ID: $id", $request->all());

        $lokerAdmin = Loker::find($id);

        if (!$lokerAdmin) {
            Log::error("Loker not found for ID: $id");
            return response()->json(['status' => 'error', 'message' => 'Lowongan not found'], 404);
        }

        if ($request->input('action') == 'verify') {
            $lokerAdmin->Verify = 'verified';
        } elseif ($request->input('action') == 'not_verify') {
            $lokerAdmin->Verify = 'pending';
        }

        $lokerAdmin->save();

        Log::info("Verification status updated successfully for Loker ID: $id", ['status' => $lokerAdmin->Verify]);

        return response()->json(['status' => 'success', 'message' => 'Lowongan status updated successfully', 'data' => $lokerAdmin], 200);
    }

    public function destroy($id)
    {
        Log::info("Deleting Loker for ID: $id");

        $lokerAdmin = Loker::find($id);

        if (!$lokerAdmin) {
            Log::error("Loker not found for ID: $id");
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        $lokerAdmin->delete();

        Log::info("Loker deleted successfully for ID: $id");
        return response()->json(['success' => true, 'message' => 'Lowongan deleted successfully'], 200);
    }

    public function store(Request $request)
    {
        Log::info('Creating new Loker', $request->all());

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
                'Verify' => 'verified',
            ]);

            Log::info('Loker created successfully.', ['loker_id' => $loker->id]);

            return response()->json(['message' => 'Loker created successfully', 'loker' => $loker], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create Loker.', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to create Loker', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info("Request received to update Loker", ['id' => $id, 'request_data' => $request->all()]);
    
        try {
            $user = auth()->user();
    
            if (!$user) {
                Log::error("Unauthorized access attempt for updating Loker", ['id' => $id]);
               
                return response()->json(['status' => 'error', 'message' => 'Unauthorized or user does not have access'], 403);           
            }
    
            $validated = $request->validate([
                'NamaPerusahaan' => 'nullable|string|max:255',
                'Posisi' => 'nullable|string|max:255',
                'Alamat' => 'nullable|string|max:255',
                'Pengalaman' => 'nullable|string|max:255',
                'Gaji' => 'nullable|string|max:255',
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
    
            Log::info("Validation passed for updating Loker", ['id' => $id, 'validated_data' => $validated]);
    
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
    
            $loker = Loker::findOrFail($id);
    
            Log::info("Loker found for update", ['id' => $id]);
    
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
    
            Log::info("Loker successfully updated", ['id' => $id]);
    
            return response()->json(['status' => 'success', 'message' => 'Lowongan updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Failed to update Loker", ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['status' => 'error','message' => 'Failed to update data','error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Manage Loker Admin.
     */
    public function manage(Request $request)
    {
        Log::info("Fetching all Loker data for admin management");
    
         $lokerAdmin = Loker::latest()->get();
    
        if ($lokerAdmin->isEmpty()) {
            Log::warning("No Loker data found");
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $lokerAdmin], 200);
    }
}
