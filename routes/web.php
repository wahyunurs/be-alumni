<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController as GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// //rute untuk menampilkan login mahasiswa
// Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
// Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// // Rute untuk menampilkan formulir login
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// // Rute untuk memproses login
// Route::post('/login', [LoginController::class, 'login'])->name('loginsubmit');

// // Rute untuk logout
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// Route::group(['middleware' => ['role:admin']], function () {
//     // Route untuk admin
// });


// // Memproses pendaftaran
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'register'])->name('registersimpan');