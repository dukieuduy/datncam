<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('client.pages.home');
    }

    public function detailProduct($id)
    {
        return view('client.pages.detail');
    }
}
