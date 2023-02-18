<?php

namespace App\Http\Controllers;

use App\User;
use App\Major;
use App\Skor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        $request->session()->flush();
        $request->session()->regenerate();

        return view('home');
    } 

    public function refreshToken(Request $request)
    {
        if ($request->header('X-CSRF-TOKEN') != csrf_token()) {
            return response()->json(['token' => csrf_token()]);
        } else {
            return response()->json(['error' => 'token is not expired']);
        }
    }
}
