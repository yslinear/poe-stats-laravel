<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function home()
    {
        return view('welcome');
    }

    public function ladder()
    {
        return view('ladder');
    }

    public function profile()
    {
        return view('profile');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}