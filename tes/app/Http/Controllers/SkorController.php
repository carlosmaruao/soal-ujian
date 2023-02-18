<?php

namespace App\Http\Controllers;

use App\Skor;
use Illuminate\Http\Request;

class SkorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
