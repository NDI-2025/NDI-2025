<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }
}