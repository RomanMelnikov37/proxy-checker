<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProxyCheckController extends Controller
{
    public function index()
    {
        return view('proxy_check.index');
    }
}
