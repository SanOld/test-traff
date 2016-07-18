<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\Data;

class Example3Controller extends Controller
{
    public function index(Data $stat)
    {
        return view('example3');
    }
}
