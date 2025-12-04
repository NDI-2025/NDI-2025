<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoiceAuth;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/voice', [VoiceAuth::class, 'showForm'])->name('voice.form');
Route::post('/voice/register', [VoiceAuth::class, 'register'])->name('voice.register');
Route::post('/voice/login', [VoiceAuth::class, 'login'])->name('voice.login');
