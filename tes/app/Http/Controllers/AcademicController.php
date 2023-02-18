<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Major;
use App\User; 
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Question;
use Illuminate\Support\Str;
use Validator;

class AcademicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users = User::orderBy('name', 'Asc')->get();
        return view('academic.users.index', ['users' => $users]);
    }  
}

