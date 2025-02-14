<?php

use App\Http\Controllers\API\Mitra\DashboardMitraController;
use App\Http\Controllers\API\Mitra\LogangMitraController;
use App\Http\Controllers\API\Mitra\LokerMitraController;
use App\Http\Controllers\API\Mitra\ProfileMitraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\ForgotPasswordController;
//login untuk mahasiswa
use App\Http\Controllers\API\SSOController;
//login untuk alumni dan admin
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\VerificationController;
//admin
use App\Http\Controllers\API\Admin\LokerAdminController;
use App\Http\Controllers\API\Admin\MasaTungguController;
use App\Http\Controllers\API\Admin\AlumniAdminController;
use App\Http\Controllers\API\Admin\LogangAdminController;
use App\Http\Controllers\API\Admin\TracerStudyController;
use App\Http\Controllers\API\Admin\ProfileAdminController;
use App\Http\Controllers\API\Admin\NewUserAlumniController;
use App\Http\Controllers\API\Admin\StatistikAdminController;
use App\Http\Controllers\API\Admin\DashboardAdminController;
use App\Http\Controllers\API\Admin\PengumumanAlumniController;
use App\Http\Controllers\API\Admin\SurveiMitraAdminController;
use App\Http\Controllers\API\Admin\DataMitraPenggunaAlumniController;
//mahasiswa
use App\Http\Controllers\API\Mahasiswa\LogangMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\CVMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\JobMhsController;
use App\Http\Controllers\API\Mahasiswa\DashboardMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\AwardMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\SkillMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\CourseMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\AcademicMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\InterestMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\InternshipMhsController;
use App\Http\Controllers\API\Mahasiswa\CVMhs\OrganizationMhsController;
//alumni
use App\Http\Controllers\API\Alumni\LokerController;
use App\Http\Controllers\API\Alumni\LogangController;
use App\Http\Controllers\API\Alumni\AlumniController;
use App\Http\Controllers\API\Alumni\RegisterController;
use App\Http\Controllers\API\Alumni\ProfileAlumniController;
use App\Http\Controllers\API\Alumni\DashboardAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\CVAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\JobAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\AwardAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\SkillAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\CourseAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\AcademicAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\InternshipAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\OrganizationAlumniController;
use App\Http\Controllers\API\Alumni\CVAlumni\InterestAlumniController;
//mitra
use App\Http\Controllers\API\Mitra\SurveiMitraController;
//STI
use App\Http\Controllers\API\PengumumanController;
//MItra




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Route untuk login SSO
Route::get('/login/mahasiswa', [SSOController::class, 'redirectToGoogle']);
Route::get('/login/mahasiswa/callback', [SSOController::class, 'handleGoogleCallback']);
Route::get('/send-token', [SSOController::class, 'sendTokenToFrontend']);

// Route untuk registrasi dan verifikasi email
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify-otp-email', [VerificationController::class, 'verifyOtp']);

// Route untuk lupa password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/verify-otp-password', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

// Route untuk login, logout, mendapatkan detail pengguna yg login
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
Route::get('/user', [LoginController::class, 'getUser'])->middleware('auth:api');

// Route untuk mendapatkan dan menghapus data alumni
Route::get('/alumni', [AlumniController::class, 'index']);
Route::get('/alumni/{id}', [AlumniController::class, 'show']);
Route::delete('/alumni/{id}', [AlumniController::class, 'destroy']);



Route::group(['middleware' => ['auth:api', 'role:alumni']], function () {
    //API Routes for Dashboard Alumni
    Route::get('/dashboardAlumni', [DashboardAlumniController::class, 'index']);
    Route::get('/dashboardAlumni-dataAlumni', [DashboardAlumniController::class, 'dataAlumni']);
    Route::get('/dashboardAlumni-loker', [DashboardAlumniController::class, 'dataLoker']);
    Route::get('/dashboardAlumni-logang', [DashboardAlumniController::class, 'dataLogang']);
    Route::get('/dashboardAlumni-loker/{id}', [DashboardAlumniController::class, 'showLoker']);
    Route::get('/dashboardAlumni-logang/{id}', [DashboardAlumniController::class, 'showLogang']);

    // API Routes for Loker
    Route::get('/loker', [LokerController::class, 'index']);
    Route::post('/loker', [LokerController::class, 'store']);
    Route::get('/loker/{id}', [LokerController::class, 'show']);
    Route::post('/loker/{id}', [LokerController::class, 'update']);
    Route::delete('/loker/{id}', [LokerController::class, 'destroy']);
    Route::get('/manageLoker', [LokerController::class, 'manage']);

    // API Routes for Logang
    Route::get('/logang', [LogangController::class, 'index']);
    Route::post('/logang', [LogangController::class, 'store']);
    Route::get('/logang/{id}', [LogangController::class, 'show']);
    Route::post('/logang/{id}', [LogangController::class, 'update']);
    Route::delete('/logang/{id}', [LogangController::class, 'destroy']);
    Route::get('/manageLogang', [LogangController::class, 'manage']);

    // API Routes for Profile Alumni
    Route::get('/profilealumni', [ProfileAlumniController::class, 'index']);
    Route::post('/profilealumni', [ProfileAlumniController::class, 'store']);
    Route::post('/change-password', [ProfileAlumniController::class, 'update']);
    Route::post('/profilealumni/edit', [ProfileAlumniController::class, 'editProfil']);


    // API Routes Academic Alumni
    Route::get('/academicAlumni', [AcademicAlumniController::class, 'index']);
    Route::post('/academicAlumni', [AcademicAlumniController::class, 'store']);
    Route::get('/academicAlumni/{id}', [AcademicAlumniController::class, 'show']);
    Route::put('/academicAlumni/{id}', [AcademicAlumniController::class, 'update']);
    Route::delete('/academicAlumni/{id}', [AcademicAlumniController::class, 'destroy']);

    // API Routes Job Alumni
    Route::get('/jobAlumni', [JobAlumniController::class, 'index']);
    Route::post('/jobAlumni', [JobAlumniController::class, 'store']);
    Route::get('/jobAlumni/{id}', [JobAlumniController::class, 'show']);
    Route::put('/jobAlumni/{id}', [JobAlumniController::class, 'update']);
    Route::delete('/jobAlumni/{id}', [JobAlumniController::class, 'destroy']);

    // API Routes Internsip Alumni
    Route::get('/internshipAlumni', [InternshipAlumniController::class, 'index']);
    Route::post('/internshipAlumni', [InternshipAlumniController::class, 'store']);
    Route::get('/internshipAlumni/{id}', [InternshipAlumniController::class, 'show']);
    Route::put('/internshipAlumni/{id}', [InternshipAlumniController::class, 'update']);
    Route::delete('/internshipAlumni/{id}', [InternshipAlumniController::class, 'destroy']);

    // API Routes Organization Alumni
    Route::get('/organizationAlumni', [OrganizationAlumniController::class, 'index']);
    Route::post('/organizationAlumni', [OrganizationAlumniController::class, 'store']);
    Route::get('/organizationAlumni/{id}', [OrganizationAlumniController::class, 'show']);
    Route::put('/organizationAlumni/{id}', [OrganizationAlumniController::class, 'update']);
    Route::delete('/organizationAlumni/{id}', [OrganizationAlumniController::class, 'destroy']);

    // API Routes Award Alumni
    Route::get('/awardAlumni', [AwardAlumniController::class, 'index']);
    Route::post('/awardAlumni', [AwardAlumniController::class, 'store']);
    Route::get('/awardAlumni/{id}', [AwardAlumniController::class, 'show']);
    Route::put('/awardAlumni/{id}', [AwardAlumniController::class, 'update']);
    Route::delete('/awardAlumni/{id}', [AwardAlumniController::class, 'destroy']);

    // API Routes course Alumni
    Route::get('/courseAlumni', [CourseAlumniController::class, 'index']);
    Route::post('/courseAlumni', [CourseAlumniController::class, 'store']);
    Route::get('/courseAlumni/{id}', [CourseAlumniController::class, 'show']);
    Route::put('/courseAlumni/{id}', [CourseAlumniController::class, 'update']);
    Route::delete('/courseAlumni/{id}', [CourseAlumniController::class, 'destroy']);

    // API Routes skill Alumni
    Route::get('/skillAlumni', [SkillAlumniController::class, 'index']);
    Route::post('/skillAlumni', [SkillAlumniController::class, 'store']);
    Route::get('/skillAlumni/{id}', [SkillAlumniController::class, 'show']);
    Route::put('/skillAlumni/{id}', [SkillAlumniController::class, 'update']);
    Route::delete('/skillAlumni/{id}', [SkillAlumniController::class, 'destroy']);

    // API Routes cetak cv Alumni
    Route::get('/cetakCvAlumni', [CvAlumniController::class, 'cetakCv']);

    //API Routes for interest Alumni
    Route::get('/interestAlumni', [InterestAlumniController::class, 'index']); // Get logged-in user's interest
    Route::post('/interestAlumni', [InterestAlumniController::class, 'storeOrUpdate']); // Store or update interest

});



Route::group(['middleware' => ['auth:api', 'role:mahasiswa']], function (): void {
    //API Routes for Dashboard Mahasiswa
    Route::get('/dashboardMahasiswa', [DashboardMhsController::class, 'index']);
    Route::get('/dashboardMahasiswa-dataMhs', [DashboardMhsController::class, 'dataMhs']);
    Route::get('/dashboardMahasiswa-logang', [DashboardMhsController::class, 'dataLogang']);
    Route::get('/dashboardMahasiswa-logang/{id}', [DashboardMhsController::class, 'showLogang']);

    //API Routes for Logang in Mahasiswa
    Route::get('/mahasiswa/logang', [LogangMhsController::class, 'index']);
    Route::get('/mahasiswa/logang/{id}', [LogangMhsController::class, 'showLogang']);

    // API Routes Academic Mahasiswa
    Route::get('/academicMhs', [AcademicMhsController::class, 'index']);
    Route::post('/academicMhs', [AcademicMhsController::class, 'store']);
    Route::get('/academicMhs/{id}', [AcademicMhsController::class, 'show']);
    Route::put('/academicMhs/{id}', [AcademicMhsController::class, 'update']);
    Route::delete('/academicMhs/{id}', [AcademicMhsController::class, 'destroy']);

    // API Routes Job Mahasiswa
    Route::get('/jobMhs', [JobMhsController::class, 'index']);
    Route::post('/jobMhs', [JobMhsController::class, 'store']);
    Route::get('/jobMhs/{id}', [JobMhsController::class, 'show']);
    Route::put('/jobMhs/{id}', [JobMhsController::class, 'update']);
    Route::delete('/jobMhs/{id}', [JobMhsController::class, 'destroy']);

    // API Routes Internsip Mahasiswa
    Route::get('/internshipMhs', [InternshipMhsController::class, 'index']);
    Route::post('/internshipMhs', [InternshipMhsController::class, 'store']);
    Route::get('/internshipMhs/{id}', [InternshipMhsController::class, 'show']);
    Route::put('/internshipMhs/{id}', [InternshipMhsController::class, 'update']);
    Route::delete('/internshipMhs/{id}', [InternshipMhsController::class, 'destroy']);

    // API Routes Organization Mahasiswa
    Route::get('/organizationMhs', [OrganizationMhsController::class, 'index']);
    Route::post('/organizationMhs', [OrganizationMhsController::class, 'store']);
    Route::get('/organizationMhs/{id}', [OrganizationMhsController::class, 'show']);
    Route::put('/organizationMhs/{id}', [OrganizationMhsController::class, 'update']);
    Route::delete('/organizationMhs/{id}', [OrganizationMhsController::class, 'destroy']);

    // API Routes Award Mahasiswa
    Route::get('/awardMhs', [AwardMhsController::class, 'index']);
    Route::post('/awardMhs', [AwardMhsController::class, 'store']);
    Route::get('/awardMhs/{id}', [AwardMhsController::class, 'show']);
    Route::put('/awardMhs/{id}', [AwardMhsController::class, 'update']);
    Route::delete('/awardMhs/{id}', [AwardMhsController::class, 'destroy']);

    // API Routes Course Mahasiswa
    Route::get('/courseMhs', [CourseMhsController::class, 'index']);
    Route::post('/courseMhs', [CourseMhsController::class, 'store']);
    Route::get('/courseMhs/{id}', [CourseMhsController::class, 'show']);
    Route::put('/courseMhs/{id}', [CourseMhsController::class, 'update']);
    Route::delete('/courseMhs/{id}', [CourseMhsController::class, 'destroy']);

    // API Routes Skill Mahasiswa
    Route::get('/skillMhs', [SkillMhsController::class, 'index']);
    Route::post('/skillMhs', [SkillMhsController::class, 'store']);
    Route::get('/skillMhs/{id}', [SkillMhsController::class, 'show']);
    Route::put('/skillMhs/{id}', [SkillMhsController::class, 'update']);
    Route::delete('/skillMhs/{id}', [SkillMhsController::class, 'destroy']);

    // API Routes cetak cv mahasiswa
    Route::get('/cetakCvMhs', [CVMhsController::class, 'cetakCv']);

    //API Routes for interest mahasiswa
    Route::get('/interestMhs', [InterestMhsController::class, 'index']); // Get logged-in user's interest
    Route::post('/interestMhs', [InterestMhsController::class, 'storeOrUpdate']); // Store or update interest

});



Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    //API Routes for Dashboard Admin
    Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index']);

    // API Routes for Admin Profile
    Route::get('/admin/profile', [ProfileAdminController::class, 'index']);
    Route::post('/admin/profile/upload', [ProfileAdminController::class, 'uploadProfilePicture']);
    Route::post('/admin/profile/update-password', [ProfileAdminController::class, 'updatePassword']);

    // API Routes loker controller
    Route::get('/lokerAdmin', [LokerAdminController::class, 'indexadmin']);
    Route::get('/lokerAdmin/{id}', [LokerAdminController::class, 'show']);
    Route::post('/lokerAdmin/{id}/verify', [LokerAdminController::class, 'verify']);
    Route::delete('/lokerAdmin/{id}', [LokerAdminController::class, 'destroy']);
    Route::post('/lokerAdmin/{id}', [LokerAdminController::class, 'update']);
    Route::post('/lokerAdmin', [LokerAdminController::class, 'store']);
    Route::get('/managelokerAdmin', [LokerAdminController::class, 'manage']);

    // API Routes for logang controller
    Route::get('/logangAdmin', [LogangAdminController::class, 'indexadmin']);
    Route::post('/logangAdmin/{id}/verify', [LogangAdminController::class, 'verify']);
    Route::post('/logangAdmin', [LogangAdminController::class, 'store']);
    Route::get('/logangAdmin/{id}', [LogangAdminController::class, 'show']);
    Route::post('/logangAdmin/{id}', [LogangAdminController::class, 'update']);
    Route::delete('/logangAdmin/{id}', [LogangAdminController::class, 'destroy']);
    Route::get('/manageLogangAdmin', [LogangAdminController::class, 'manage']);

    // API Routes for pengumuman
    Route::get('/pengumumanAlumni', [PengumumanAlumniController::class, 'index']);
    Route::get('/pengumumanAlumni/{id}', [PengumumanAlumniController::class, 'show']);
    Route::post('/pengumumanAlumni', [PengumumanAlumniController::class, 'store']);
    Route::post('/pengumumanAlumni/{id}', [PengumumanAlumniController::class, 'update']);
    Route::delete('/pengumumanAlumni/{id}', [PengumumanAlumniController::class, 'destroy']);

    // API Routes for Data alumni
    Route::get('/dataAlumniAdmin/{id_alumni}/cv', [AlumniAdminController::class, 'showCV']);
    Route::get('/dataAlumniAdmin/search', [AlumniAdminController::class, 'search']);

    // API Routes for statistik controller
    Route::get('/statistik', [StatistikAdminController::class, 'index']);
    Route::get('statistik/{id}', [StatistikAdminController::class, 'show']);
    Route::post('/statistik', [StatistikAdminController::class, 'store']);
    Route::put('/statistik/{id}', [StatistikAdminController::class, 'update']);
    Route::delete('/statistik/{id}', [StatistikAdminController::class, 'destroy']);

    //API Routes for data tracer study para alumni
    Route::get('/cekTracerstudy', [TracerStudyController::class, 'cekAlumni']); //ini menampilkan jumlah data alumni sesuai status dengan filter setiap tahun lulus
    Route::get('/tracerstudy', [TracerStudyController::class, 'index']); //ini isian data alumni sesuai statusnya
    Route::get('/tahunLulusTracerstudy', [TracerStudyController::class, 'getTahunLulus']); // buat isi dropdown tahun lulus  secara otomatis

    //API Routes for data masa tunggu para alumni
    Route::get('/cekMasaTunggu', [MasaTungguController::class, 'cekMasaTunggu']); //ini menampilkan jumlah data masa tunggu alumni sesuai dengan filter setiap tahun lulus
    Route::get('/masaTunggu', [MasaTungguController::class, 'index']); //ini isian data alumni sesuai masa tunggu
    Route::get('/tahunLulusMasaTunggu', [MasaTungguController::class, 'getTahunLulus']); // buat isi dropdown tahun lulus masa tunggu secara otomatis

    //API Routes for import excel new user alumni
    Route::post('/importAlumni', [NewUserAlumniController::class, 'import']);

    Route::get('/dataMitra', [DataMitraPenggunaAlumniController::class, 'getAllMitra']);
    Route::get('/dataMitra/search', [DataMitraPenggunaAlumniController::class, 'searchMitra'])->name('dataMitra.searchMitra');
    Route::get('/dataMitra/{id}', [DataMitraPenggunaAlumniController::class, 'getDetailMitra']);

    // API Routes survei mitra
    Route::get('/surveiMitraAdmin', [SurveiMitraAdminController::class, 'index']);
});

Route::group(['middleware' => ['auth:api', 'role:mitra|admin']], function (){
    Route::get('/dataAlumniAdmin', [AlumniAdminController::class, 'index']);
});

Route::group(['middleware' => ['auth:api', 'role:mitra']], function () {
    // Pencarian Nama Alumni
    Route::get('/surveiSearch', [SurveiMitraController::class, 'search']);

    // API Routes for Dashboard
    Route::get('/dashboardMitra', [DashboardMitraController::class, 'getStatistics']);

    // API Routes survei mitra
    Route::get('/surveiMitra', [SurveiMitraController::class, 'index']);
    Route::post('/surveiMitra', [SurveiMitraController::class, 'store']);
    Route::put('/surveiMitra/{id}', [SurveiMitraController::class, 'update']);
    Route::get('/surveiMitra/{id}', [SurveiMitraController::class, 'show']);
    Route::delete('/surveiMitra/{id}', [SurveiMitraController::class, 'destroy']);

    // API Routes for Data alumni
    Route::get('/dataAlumniMitra', [AlumniAdminController::class, 'index']);
    Route::get('/dataAlumniMitra/{id_alumni}/cv', [AlumniAdminController::class, 'showCV']);
    Route::get('/dataAlumniMitra/search', [AlumniAdminController::class, 'search']);

    // API Routes for Mitra Profile
    Route::get('/mitra/profile', [ProfileMitraController::class, 'index']);
    Route::post('/mitra/profile/upload', [ProfileMitraController::class, 'uploadProfilePicture']);
    Route::post('/mitra/profile/update-password', [ProfileMitraController::class, 'updatePassword']);

    // API Routes loker controller
    Route::get('/lokerMitra', [LokerMitraController::class, 'indexmitra']);
    Route::get('/lokerMitra/{id}', [LokerMitraController::class, 'show']);
    Route::delete('/lokerMitra/{id}', [LokerMitraController::class, 'destroy']);
    Route::post('/lokerMitra/{id}', [LokerMitraController::class, 'update']);
    Route::post('/lokerMitra', [LokerMitraController::class, 'store']);
    Route::get('/managelokerMitra', [LokerMitraController::class, 'manage']);
});
//API Route for pengumuman STI
Route::get('/pengumuman', [PengumumanController::class, 'index']);
Route::get('/pengumuman/{id}', [PengumumanController::class, 'show']);

