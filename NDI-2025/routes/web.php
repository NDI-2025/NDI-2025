<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoiceAuth;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Inscription VOCALE UNIQUEMENT
Route::get('/register', [VoiceAuth::class, 'showVoiceRegisterForm'])->name('register');
Route::post('/api/voice-register-account', [VoiceAuth::class, 'registerVoiceAccount'])->name('voice.register.account');

// Route login obligatoire pour Laravel (VOCAL uniquement)
Route::get('/login', [VoiceAuth::class, 'showLoginForm'])->name('login');

// Connexion vocale (accessible sans auth)
Route::get('/voice-login', [VoiceAuth::class, 'showLoginForm'])->name('voice.login');

// API authentification vocale
Route::post('/api/voice-auth', [VoiceAuth::class, 'authenticate'])->name('voice.authenticate');

// Routes protégées (nécessite authentification)
Route::middleware('auth')->group(function () {
    Route::post('/voice-logout', [VoiceAuth::class, 'logout'])->name('voice.logout');
});

Route::get('/pitch', function () {
    return view('audio-visualizer');
});
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/snake', function () {
    return view('snake');
})->name('snake');