<?php

namespace Database\Seeders;

use App\Models\Logang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataLogangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Logang::create([
            'user_id' => '8',//Fanuel Phalosa Handiono
            'NamaPerusahaan' => 'PT Bank Central Asia Tbk',
            'Posisi' => 'Pegawai',
            'Alamat' => 'BCA Wisma Asia 2 Jl. Brigjen Katamso No.1, RT.3/RW.7, Kota Bambu Sel., Kec. Palmerah, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11420',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Dapat Menguasai Materi Tentang Ilmu Komputer
Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.bca.co.id/',
            'Email' => 'phalosafanuel@gmail.com',
            'no_hp' => '',
            'Tags' => 'BCA, Pegawai, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '26',//Achmad Naila Muna Ramadhani
            'NamaPerusahaan' => 'PT.Bank Mandiri (Persero) Tbk.',
            'Posisi' => 'Teller',
            'Alamat' => 'Jl. Jenderal Gatot Subroto Kav. 36-38 Jakarta 12190 Indonesia',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.bca.co.id/',
            'Email' => 'armdhni310801@gmail.com',
            'no_hp' => '0812345678',
            'Tags' => 'Mandiri, Teller, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '9',//Stanley Mesa Ariel
            'NamaPerusahaan' => 'PT Visi Prima Nusantara',
            'Posisi' => 'Marketing',
            'Alamat' => 'Rukan Artha Gading, Jl. Boulevard Artha Gading Blok C29, Klp. Gading Bar., Kec. Klp. Gading, Jkt Utara, Daerah Khusus Ibukota Jakarta 14240',
            'Pengalaman' => 'Minimal 2 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan',
            'Website' => 'https://visiprima.id/',
            'Email' => 'stanleymesa2001@gmail.com',
            'no_hp' => '',
            'Tags' => 'Visi Prima Nusantara, Marketing, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '25',//Afif Satria Pratama
            'NamaPerusahaan' => 'PT. Astra Graphia Information Technology',
            'Posisi' => 'Sales',
            'Alamat' => 'Jl. Kramat Raya Jakarta Pusat',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://visiprima.id/',
            'Email' => 'afifsatria25@gmail.com',
            'no_hp' => '',
            'Tags' => 'Astra, Sales, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '22',//Muhammad Kharirushofa
            'NamaPerusahaan' => 'PT. Mitra Integrasi Informatika',
            'Posisi' => 'Developer',
            'Alamat' => 'Jl. Kramat Raya Jakarta Pusat',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Menguasai berbagai bahasa pemrograman
Teliti dan cekatan',
            'Website' => 'https://www.mii.co.id/karir',
            'Email' => 'muhammadaril5525@gmail.com',
            'no_hp' => '',
            'Tags' => 'Astra, Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '33',//Rinaldi Hendrawan
            'NamaPerusahaan' => 'PT. Hartono Istana Teknologi',
            'Posisi' => 'Pegawai',
            'Alamat' => 'Jl. Semarang - Demak No.KM.9, Trimulyo, Sriwulan, Kec. Sayung, Kabupaten Demak, Jawa Tengah 59563',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Teliti
Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.polytron.co.id/',
            'Email' => 'rinaldih84@gmail.com',
            'no_hp' => '',
            'Tags' => 'Polytron, Pegawai, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '54',//Zarek Gema Galgani
            'NamaPerusahaan' => 'PT. ADIRA Dinamika Multi Finance, Tbk.',
            'Posisi' => 'Debt Collector',
            'Alamat' => 'Jl. Lengkong No. 160 RT 03/13 Mertasinga, Cilacap, Jawa Tengah',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://www.adira.co.id/karir/index',
            'Email' => 'zarekgema8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Adira, Debt Collector, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '67',//Harish Trio Adityawan
            'NamaPerusahaan' => 'Unilever Indonesia',
            'Posisi' => 'Sales Executive',
            'Alamat' => 'Jalan Jababeka Raya Blok O - KIJ, Wangunharja, Kec. Cikarang Utara, Kabupaten Bekasi, Jawa Barat 17530',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan',
            'Website' => 'https://careers.unilever.com/location/indonesia-jobs/34155/1643084/2',
            'Email' => 'haradityawan@gmail.com',
            'no_hp' => '',
            'Tags' => 'Unilever, Sales Executive, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '84',//Habib Kuncoro Jati
            'NamaPerusahaan' => 'PT Semen Grobogan',
            'Posisi' => 'Quality Checking',
            'Alamat' => 'Sugihmanik,Tanggungharjo,Grobogan',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://semen-grobogan.com/id/karir',
            'Email' => 'habibkun.9102@gmail.com',
            'no_hp' => '',
            'Tags' => 'Semen, Quality Checking, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '91',//Haris Adiyatma Farhan
            'NamaPerusahaan' => 'PT. MULTINDO AUTO FINANCE',
            'Posisi' => 'Android Developer',
            'Alamat' => 'Jl. Pandanaran no. 119A, Kel. Mugasari, Kec. Semarang Selatan, Semarang, Jawa Tengah - 50243',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'adiyatmaharis8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Multindo, Android Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '10',//Haris Adiyatma Farhan
            'NamaPerusahaan' => 'PT. MULTINDO AUTO FINANCE',
            'Posisi' => 'Android Developer',
            'Alamat' => 'Jl. Pandanaran no. 119A, Kel. Mugasari, Kec. Semarang Selatan, Semarang, Jawa Tengah - 50243',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'adiyatmaharis8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Multindo, Android Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending'
        ]);
        Logang::create([
            'user_id' => '8',//Fanuel Phalosa Handiono
            'NamaPerusahaan' => 'PT Bank Central Asia Tbk',
            'Posisi' => 'Pegawai',
            'Alamat' => 'BCA Wisma Asia 2 Jl. Brigjen Katamso No.1, RT.3/RW.7, Kota Bambu Sel., Kec. Palmerah, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11420',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Dapat Menguasai Materi Tentang Ilmu Komputer
Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.bca.co.id/',
            'Email' => 'phalosafanuel@gmail.com',
            'no_hp' => '',
            'Tags' => 'BCA, Pegawai, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '26',//Achmad Naila Muna Ramadhani
            'NamaPerusahaan' => 'PT.Bank Mandiri (Persero) Tbk.',
            'Posisi' => 'Teller',
            'Alamat' => 'Jl. Jenderal Gatot Subroto Kav. 36-38 Jakarta 12190 Indonesia',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.bca.co.id/',
            'Email' => 'armdhni310801@gmail.com',
            'no_hp' => '',
            'Tags' => 'Mandiri, Teller, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '9',//Stanley Mesa Ariel
            'NamaPerusahaan' => 'PT Visi Prima Nusantara',
            'Posisi' => 'Marketing',
            'Alamat' => 'Rukan Artha Gading, Jl. Boulevard Artha Gading Blok C29, Klp. Gading Bar., Kec. Klp. Gading, Jkt Utara, Daerah Khusus Ibukota Jakarta 14240',
            'Pengalaman' => 'Minimal 2 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan',
            'Website' => 'https://visiprima.id/',
            'Email' => 'stanleymesa2001@gmail.com',
            'no_hp' => '',
            'Tags' => 'Visi Prima Nusantara, Marketing, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '25',//Afif Satria Pratama
            'NamaPerusahaan' => 'PT. Astra Graphia Information Technology',
            'Posisi' => 'Sales',
            'Alamat' => 'Jl. Kramat Raya Jakarta Pusat',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://visiprima.id/',
            'Email' => 'afifsatria25@gmail.com',
            'no_hp' => '',
            'Tags' => 'Astra, Sales, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '22',//Muhammad Kharirushofa
            'NamaPerusahaan' => 'PT. Mitra Integrasi Informatika',
            'Posisi' => 'Developer',
            'Alamat' => 'Jl. Kramat Raya Jakarta Pusat',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Menguasai berbagai bahasa pemrograman
Teliti dan cekatan',
            'Website' => 'https://www.mii.co.id/karir',
            'Email' => 'muhammadaril5525@gmail.com',
            'no_hp' => '',
            'Tags' => 'Astra, Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '33',//Rinaldi Hendrawan
            'NamaPerusahaan' => 'PT. Hartono Istana Teknologi',
            'Posisi' => 'Pegawai',
            'Alamat' => 'Jl. Semarang - Demak No.KM.9, Trimulyo, Sriwulan, Kec. Sayung, Kabupaten Demak, Jawa Tengah 59563',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Teliti
Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.polytron.co.id/',
            'Email' => 'rinaldih84@gmail.com',
            'no_hp' => '',
            'Tags' => 'Polytron, Pegawai, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '54',//Zarek Gema Galgani
            'NamaPerusahaan' => 'PT. ADIRA Dinamika Multi Finance, Tbk.',
            'Posisi' => 'Debt Collector',
            'Alamat' => 'Jl. Lengkong No. 160 RT 03/13 Mertasinga, Cilacap, Jawa Tengah',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://www.adira.co.id/karir/index',
            'Email' => 'zarekgema8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Adira, Debt Collector, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '67',//Harish Trio Adityawan
            'NamaPerusahaan' => 'Unilever Indonesia',
            'Posisi' => 'Sales Executive',
            'Alamat' => 'Jalan Jababeka Raya Blok O - KIJ, Wangunharja, Kec. Cikarang Utara, Kabupaten Bekasi, Jawa Barat 17530',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan',
            'Website' => 'https://careers.unilever.com/location/indonesia-jobs/34155/1643084/2',
            'Email' => 'haradityawan@gmail.com',
            'no_hp' => '',
            'Tags' => 'Unilever, Sales Executive, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '84',//Habib Kuncoro Jati
            'NamaPerusahaan' => 'PT Semen Grobogan',
            'Posisi' => 'Quality Checking',
            'Alamat' => 'Sugihmanik,Tanggungharjo,Grobogan',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://semen-grobogan.com/id/karir',
            'Email' => 'habibkun.9102@gmail.com',
            'no_hp' => '',
            'Tags' => 'Semen, Quality Checking, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '91',//Haris Adiyatma Farhan
            'NamaPerusahaan' => 'PT. MULTINDO AUTO FINANCE',
            'Posisi' => 'Android Developer',
            'Alamat' => 'Jl. Pandanaran no. 119A, Kel. Mugasari, Kec. Semarang Selatan, Semarang, Jawa Tengah - 50243',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'adiyatmaharis8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Multindo, Android Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '10',//Haris Adiyatma Farhan
            'NamaPerusahaan' => 'PT. MULTINDO AUTO FINANCE',
            'Posisi' => 'Android Developer',
            'Alamat' => 'Jl. Pandanaran no. 119A, Kel. Mugasari, Kec. Semarang Selatan, Semarang, Jawa Tengah - 50243',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'adiyatmaharis8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Multindo, Android Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT. MULTINDO AUTO FINANCE',
            'Posisi' => 'Android Developer',
            'Alamat' => 'Jl. Pandanaran no. 119A, Kel. Mugasari, Kec. Semarang Selatan, Semarang, Jawa Tengah - 50243',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'adiyatmaharis8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Multindo, Android Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);
        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'Core Initiative Studio',
            'Posisi' => 'Web Developer',
            'Alamat' => 'Code Margonda Co-working Space, Depok Town Square Lt.2, Beji, Depok',
            'Pengalaman' => 'Fresh Graduate',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Web',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'saputra.langgang.nara@gmail.com',
            'no_hp' => '',
            'Tags' => 'Core Initiative Studio, Web Developer, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);
        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT.Shopee Internasional Indonesia',
            'Posisi' => 'Developer',
            'Alamat' => 'Sopo Del Building, The Sky, L30, Lot 10.1-6 Jl. Mega Kuningan Barat III, RT.3/RW.3 Kuningan Timur, Setiabudi, Jakarta Selatan DKI Jakarta 12950',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'tifan@proton.me',
            'no_hp' => '',
            'Tags' => 'Shopee, Developer, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);
        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT Semen Grobogan',
            'Posisi' => 'Quality Checking',
            'Alamat' => 'Sugihmanik,Tanggungharjo,Grobogan',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://semen-grobogan.com/id/karir',
            'Email' => 'habibkun.9102@gmail.com',
            'no_hp' => '',
            'Tags' => 'Semen, Quality Checking, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'pending',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT. MULTINDO AUTO FINANCE',
            'Posisi' => 'Android Developer',
            'Alamat' => 'Jl. Pandanaran no. 119A, Kel. Mugasari, Kec. Semarang Selatan, Semarang, Jawa Tengah - 50243',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'adiyatmaharis8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Multindo, Android Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT. MULTINDO AUTO FINANCE',
            'Posisi' => 'Android Developer',
            'Alamat' => 'Jl. Pandanaran no. 119A, Kel. Mugasari, Kec. Semarang Selatan, Semarang, Jawa Tengah - 50243',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Mengembangkan aplikasi yang sudah tersedia
Maintanance aplikasi yang mengalami kendala
Membuat aplikasi berbasis Android',
            'Website' => 'https://multindo.co.id/karir.html',
            'Email' => 'adiyatmaharis8@gmail.com',
            'no_hp' => '',
            'Tags' => 'Multindo, Android Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);
        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT Bank Central Asia Tbk',
            'Posisi' => 'Pegawai',
            'Alamat' => 'BCA Wisma Asia 2 Jl. Brigjen Katamso No.1, RT.3/RW.7, Kota Bambu Sel., Kec. Palmerah, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11420',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Dapat Menguasai Materi Tentang Ilmu Komputer
Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.bca.co.id/',
            'Email' => 'phalosafanuel@gmail.com',
            'no_hp' => '',
            'Tags' => 'BCA, Pegawai, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',

            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT.Bank Mandiri (Persero) Tbk.',
            'Posisi' => 'Teller',
            'Alamat' => 'Jl. Jenderal Gatot Subroto Kav. 36-38 Jakarta 12190 Indonesia',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Minimal S1
Fluent dalam Bahasa Inggris',
            'Website' => 'https://karir.bca.co.id/',
            'Email' => 'armdhni310801@gmail.com',
            'no_hp' => '',
            'Tags' => 'Mandiri, Teller, Full Time',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT Visi Prima Nusantara',
            'Posisi' => 'Marketing',
            'Alamat' => 'Rukan Artha Gading, Jl. Boulevard Artha Gading Blok C29, Klp. Gading Bar., Kec. Klp. Gading, Jkt Utara, Daerah Khusus Ibukota Jakarta 14240',
            'Pengalaman' => 'Minimal 2 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan',
            'Website' => 'https://visiprima.id/',
            'Email' => 'stanleymesa2001@gmail.com',
            'no_hp' => '',
            'Tags' => 'Visi Prima Nusantara, Marketing, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT. Astra Graphia Information Technology',
            'Posisi' => 'Sales',
            'Alamat' => 'Jl. Kramat Raya Jakarta Pusat',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Sementara',
            'Deskripsi' => 'Dapat bekerja dibawah tekanan
Teliti dan cekatan
Dapat bekerja dilapangan',
            'Website' => 'https://visiprima.id/',
            'Email' => 'afifsatria25@gmail.com',
            'no_hp' => '',
            'Tags' => 'Astra, Sales, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);

        Logang::create([
            'user_id' => '1',//Koordinator
            'NamaPerusahaan' => 'PT. Mitra Integrasi Informatika',
            'Posisi' => 'Developer',
            'Alamat' => 'Jl. Kramat Raya Jakarta Pusat',
            'Pengalaman' => 'Minimal 1 Tahun',
            'Gaji' => 'Gaji per shift',
            'TipeMagang' => 'Full Time',
            'Deskripsi' => 'Menguasai berbagai bahasa pemrograman
Teliti dan cekatan',
            'Website' => 'https://www.mii.co.id/karir',
            'Email' => 'muhammadaril5525@gmail.com',
            'no_hp' => '',
            'Tags' => 'Astra, Developer, Sementara',
            'Logo' => 'default_logo.png',
            'Verify' => 'verified',
            'MasaBerlaku' => '2024-12-31'
        ]);
        
    }
}
