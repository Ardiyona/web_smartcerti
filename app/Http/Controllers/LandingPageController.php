<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('landingpage'); // Mengarah ke view 'landingpage.blade.php'
    }
}
