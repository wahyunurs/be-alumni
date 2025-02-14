<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class NewUserAlumniController extends Controller
{
    public function import(Request $request)
    {
        try {
            Log::info('Memulai proses impor alumni.');

            // Validasi input
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,csv',
                'category' => 'required|string|in:alumni',
            ]);

            if ($validator->fails()) {
                Log::warning('Validasi gagal: ' . implode(', ', $validator->errors()->all()));
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                ], 422);
            }

            $file = $request->file('file');
            Log::info('File diterima: ' . $file->getClientOriginalName());
            $filePath = $file->getPathname();

            // Membaca file Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(); // Mengonversi sheet menjadi array

            $duplicateStudents = [];
            $successCount = 0;

            foreach ($rows as $key => $row) {
                if ($key === 0) continue; // Skip header row

                // Validasi jumlah kolom minimal
                if (count($row) < 12) {
                    Log::warning('Jumlah kolom kurang dari 12: ' . implode(',', $row));
                    return response()->json([
                        'status' => 'error',
                        'message' => 'File Excel harus memiliki minimal 12 kolom.',
                    ], 422);
                }

                // Validasi NIM dan Email unik
                $existingAlumni = Alumni::where('nim', $row[2])->exists();
                $existingUser = User::where('email', $row[3])->exists();

                if ($existingAlumni || $existingUser) {
                    $duplicateStudents[] = "Alumni dengan NIM {$row[2]} atau Email {$row[3]} sudah ada.";
                    Log::info('Data duplikat ditemukan: NIM ' . $row[2] . ' atau Email ' . $row[3]);
                    continue;
                }

                // Format nomor HP
                $cleanedPhone = preg_replace('/\D/', '', $row[4]);

                // Buat user baru untuk alumni
                $user = User::create([
                    'name' => $row[1], // Nama 
                    'email' => $row[3], // Email
                    'password' => bcrypt("Dinus-" . $row[2]), // Password default
                    'role' => 'alumni',
                    'email_verified_at' => Carbon::now(),
                ]);

                // Tetapkan role alumni
                $user->syncRoles(['alumni']);

                // Simpan data ke tabel alumni
                Alumni::create([
                    'user_id' => $user->id,
                    'name' => $row[1], // Nama
                    'nim' => $row[2], // NIM
                    'email' => $row[3],
                    'no_hp' => $cleanedPhone, // Nomor HP (sudah diformat)
                    'tahun_masuk' => $this->extractYearFromNim($row[2]), // Ambil tahun dari NIM
                    'tahun_lulus' => $row[5], // Tahun lulus
                    'bulan_lulus' => $row[6] ?? 'Tidak Diketahui',  // Set default karena tidak ada di file
                    'wisuda' => $row[7], // Data wisuda
                    'status' => $row[8], // Status default
                    'bidang_job' => null,
                    'jns_job' => $row[9] ?? null, // Jenis pekerjaan
                    'nama_job' => $row[11] ?? null, // Nama instansi
                    'jabatan_job' => $row[12] ?? null,
                    'lingkup_job' => $row[10] ?? null, // Skala instansi
                    'bulan_masuk_job' => null,
                    'biaya_studi' => null,
                    'jenjang_pendidikan' => null,
                    'universitas' => null,
                    'program_studi' => null,
                ]);

                $successCount++;
                Log::info("Alumni berhasil diimpor: {$row[1]}");
            }

            // Buat pesan hasil impor
            $message = $successCount > 0
                ? "$successCount data alumni berhasil diimpor."
                : "Tidak ada data baru yang diimpor.";

            if (!empty($duplicateStudents)) {
                $message .= " Berikut data duplikat: \n" . implode("\n", $duplicateStudents);
            }

            Log::info('Proses impor selesai.');

            return response()->json([
                'status' => 'success',
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengimpor data: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengimpor data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ekstrak tahun masuk dari NIM.
     *
     * @param string $nim
     * @return int
     */
    private function extractYearFromNim(string $nim)
    {
        $parts = explode('.', $nim);

        if (isset($parts[1]) && preg_match('/^\d{4}$/', $parts[1])) {
            return (int) $parts[1];
        }

        return 0;
    }
}
