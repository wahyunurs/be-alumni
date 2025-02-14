<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Register</title>
    <style>
        .left-box {
            background-color: #007BFF;
            color: white;
        }

        .right-box {
            padding: 20px;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .btn-submit {
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .form-group label {
            margin-bottom: 5px;
        }

        .d-none {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 col-12 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                <div class="featured-image mb-3">
                    <!-- Uncomment and add your logo or image here -->
                    <!--<img src="images/1.png" class="img-fluid" style="width: 250px;">-->
                </div>
                <p class="fs-2 text-white">Alumni</p>
                <small class="text-white text-wrap text-center">Universitas Dian Nuswantoro</small>
            </div>
            <div class="col-md-6 col-12 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Register</h2>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nama -->
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" class="form-control" required>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group mb-3">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="alumni">Alumni</option>
                               
                            </select>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="form-group mb-3">
                            <label for="jns_kelamin">Jenis Kelamin</label>
                            <select id="jns_kelamin" name="jns_kelamin" class="form-select" required>
                                <option selected disabled>Pilih Jenis Kelamin</option>
                                <option value="Perempuan">Perempuan</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                            </select>
                        </div>

                        <!-- NIM -->
                        <div class="form-group mb-3">
                            <label for="nim">NIM</label>
                            <input id="nim" type="text" name="nim" value="{{ old('nim') }}" class="form-control" placeholder="Masukkan NIM Sewaktu Mahasiswa" required>
                        </div>

                        <!-- Tahun Masuk -->
                        <div class="form-group mb-3">
                            <label for="tahun_masuk">Tahun Masuk</label>
                            <input id="tahun_masuk" type="number" name="tahun_masuk" value="{{ old('tahun_masuk') }}" class="form-control" placeholder="Masukkan Tahun Masuk Perkuliahan" min="1900" max="2024" required>
                        </div>

                        <!-- Tahun Lulus -->
                        <div class="form-group mb-3">
                            <label for="tahun_lulus">Tahun Lulus</label>
                            <input id="tahun_lulus" type="number" name="tahun_lulus" value="{{ old('tahun_lulus') }}" class="form-control" placeholder="Masukkan Tahun Lulus Perkuliahan" min="1900" max="2024" required>
                        </div>

                        <!-- No HP -->
                        <div class="form-group mb-3">
                            <label for="no_hp">Nomor HP (WhatsApp)</label>
                            <input id="no_hp" type="number" name="no_hp" value="{{ old('no_hp') }}" class="form-control" placeholder="Masukkan Nomor HP yang Terhubung WhatsApp" required>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <div class="row mb-3">
                                <label for="no_hp">Jelaskan Status Anda Saat Ini</label>
                                <div class="col-sm-9">  
                                    <select class="selectpicker form-control" data-live-search="true" name="status" id="status" value="{{ old('status') }}" onchange="handleStatusChange()">
                                        <option selected disabled>Pilih Status Anda Saat Ini</option>
                                        <option value="Bekerja Full Time">Bekerja Full Time</option>
                                        <option value="Bekerja Part Time">Bekerja Part Time</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Melanjutkan Pendidikan">Melanjutkan Pendidikan</option>
                                        <option value="Tidak Bekerja Tetapi Sedang Mencari Pekerjaan">Tidak Bekerja Tetapi Sedang Mencari Pekerjaan</option>
                                        <option value="Belum Memungkinkan Bekerja">Belum Memungkinkan Bekerja</option>
                                        <option value="Menikah/Mengurus Keluarga">Menikah/Mengurus Keluarga</option>
                                    </select>      
                                </div>  
                            </div> 
                            <!-- error message untuk status -->
                            @error('status')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Form 9-13, hanya ditampilkan jika status bekerja full time/part time/Wiraswasta -->
                        <div id="job-fields" class="d-none">
                            <!-- form 9 -->
                            <div class="form-group">
                                <div class="row mb-3">
                                    <label for="no_hp">Bidang Pekerjaan</label>
                                    <div class="col-sm-9">
                                        <select class="selectpicker form-control" data-live-search="true" name="bidang_job" value="{{ old('bidang_job') }}">
                                            <option selected disabled>Pilih Bidang Pekerjaan</option>
                                            <option value="Infokom">Infokom</option>
                                            <option value="Non Infokom">Non Infokom</option>
                                        </select>    
                                    </div>  
                                </div>  
                                <!-- error message untuk bidang_job -->
                                @error('bidang_job')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- form 10 -->
                            <div class="form-group">
                                <div class="row mb-3">
                                    <label for="no_hp">Kategori Pekerjaan</label>
                                    <div class="col-sm-9">  
                                        <select class="selectpicker form-control" data-live-search="true" name="jns_job" value="{{ old('jns_job') }}">
                                            <option selected disabled>Pilih Kategori Perusahaan Tempat Anda Bekerja</option>
                                            <option value="Perusahaan Swasta">Perusahaan Swasta</option>
                                            <option value="Perusahaan Nirlaba">Perusahaan Nirlaba</option>
                                            <option value="Institusi/Organisasi Multilateral">BUMN/BUMD</option>
                                            <option value="Lembaga Pemerintah">Lembaga Pemerintah</option>
                                            <option value="BUMN/BUMD">BUMN/BUMD</option>
                                            <option value="Wirausaha">Wirausaha</option>
                                        </select>      
                                    </div>  
                                </div> 
                                <!-- error message untuk jns_job -->
                                @error('jns_job')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- form 11 -->
                            <div class="form-group">
                                <div class="row mb-3">
                                    <label for="no_hp">Nama Instansi</label>
                                    <div class="col-sm-9">        
                                        <input type="text" class="form-control @error('nama_job') is-invalid @enderror" name="nama_job" value="{{ old('nama_job') }}" placeholder="Masukkan Nama Instansi">
                                    </div>  
                                </div> 
                                <!-- error message untuk nim -->
                                @error('nama_job')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- form 12 -->
                            <div class="form-group">
                                <div class="row mb-3">
                                    <label for="no_hp">Jabatan Pekerjaan</label>
                                    <div class="col-sm-9">  
                                        <select class="selectpicker form-control" data-live-search="true" name="jabatan_job" value="{{ old('jabatan_job') }}">
                                            <option selected disabled>Pilih Jabatan Pekerjaan Anda</option>
                                            <option value="Founder">Founder</option>
                                            <option value="Co-Founder">Co-Founder</option>
                                            <option value="Staff">Staff</option>
                                            <option value="Freelance">Freelance</option>
                                        </select>
                                    </div>  
                                </div> 
                                <!-- error message untuk jabatan_job -->
                                @error('jabatan_job')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- form 13 -->
                            <div class="form-group">
                                <div class="row mb-3">
                                    <label for="no_hp">Tingkat Pekerjaan</label>
                                    <div class="col-sm-9">
                                        <select class="selectpicker form-control" data-live-search="true" name="lingkup_job" value="{{ old('lingkup_job') }}">
                                            <option selected disabled>Pilih Tingkat Pekerjaan</option>
                                            <option value="Lokal/Wilayah Tidak Berbadan Hukum">Lokal/Wilayah Tidak Berbadan Hukum</option>
                                            <option value="Lokal/Wilayah Berbadan Hukum">Lokal/Wilayah Berbadan Hukum</option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Multinasional">Multinasional</option>
                                            <option value="Internasional">Internasional</option>
                                        </select>    
                                    </div>  
                                </div>  
                                <!-- error message untuk lingkup_job -->
                                @error('lingkup_job')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form 14-17, hanya ditampilkan jika melanjutkan studi -->
                        <div id="education-fields" class="d-none">
                            <!-- form 14 -->
                            <div class="form-group">
                                <div class="row mb-3">
                                    <label for="no_hp">Sumber Biaya Melanjutkan Studi</label>
                                    <div class="col-sm-9">
                                        <select class="selectpicker form-control" data-live-search="true" name="biaya_studi" value="{{ old('biaya_studi') }}">
                                            <option selected disabled>Pilih Sumber Biaya Melanjutkan Studi</option>
                                            <option value="Sendiri">Sendiri</option>
                                            <option value="Beasiswa">Beasiswa</option>
                                        </select>    
                                    </div>  
                                </div>  
                                                                <!-- error message untuk biaya_studi -->
                                                                @error('biaya_studi')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                            
                                                        <!-- form 15 -->
                                                        <div class="form-group">
                                                            <div class="row mb-3">
                                                                <label for="no_hp">Jenjang Pendidikan</label>
                                                                <div class="col-sm-9">
                                                                    <select class="selectpicker form-control" data-live-search="true" name="jenjang_pendidikan" value="{{ old('jenjang_pendidikan') }}">
                                                                        <option selected disabled>Pilih Jenjang Pendidikan</option>
                                                                        <option value="S2">S2</option>
                                                                        <option value="S3">S3</option>
                                                                    </select>    
                                                                </div>  
                                                            </div>  
                                                            <!-- error message untuk jenjang_pendidikan -->
                                                            @error('jenjang_pendidikan')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                            
                                                        <!-- form 16 -->
                                                        <div class="form-group">
                                                            <div class="row mb-3">
                                                                <label for="no_hp">Nama Universitas</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control @error('universitas') is-invalid @enderror" name="universitas" value="{{ old('universitas') }}" placeholder="Masukkan Nama Universitas">
                                                                </div>  
                                                            </div> 
                                                            <!-- error message untuk universitas -->
                                                            @error('universitas')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                            
                                                        <!-- form 17 -->
                                                        <div class="form-group">
                                                            <div class="row mb-3">
                                                                <label for="no_hp">Program Studi</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control @error('program_studi') is-invalid @enderror" name="program_studi" value="{{ old('program_studi') }}" placeholder="Masukkan Program Studi">
                                                                </div>  
                                                            </div> 
                                                            <!-- error message untuk program_studi -->
                                                            @error('program_studi')
                                                                <div class="alert alert-danger mt-2">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                            
                                                    <!-- Submit Button -->
                                                    <div class="form-group mb-3">
                                                        <button type="submit" class="btn btn-submit">Daftar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <script>
                                    function handleStatusChange() {
                                        var status = document.getElementById('status').value;
                                        var jobFields = document.getElementById('job-fields');
                                        var educationFields = document.getElementById('education-fields');
                            
                                        // Clear previous selections
                                        jobFields.classList.add('d-none');
                                        educationFields.classList.add('d-none');
                            
                                        // Show fields based on selected status
                                        if (status === 'Bekerja Full Time' || status === 'Bekerja Part Time' || status === 'Wiraswasta') {
                                            jobFields.classList.remove('d-none');
                                        } else if (status === 'Melanjutkan Pendidikan') {
                                            educationFields.classList.remove('d-none');
                                        }
                                    }
                                </script>
                            </body>
                            </html>
                            