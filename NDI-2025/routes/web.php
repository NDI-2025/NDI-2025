<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pitch', function () {
    return view('audio-visualizer');
});
Route::get('/about', function () {
    return view('about');
})->name('about');
