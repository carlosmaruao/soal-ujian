<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        if (Auth::user()->hasRole('admin')) {
            $this->redirectTo = route('admin.users.index');
            return $this->redirectTo;
        } else if (Auth::user()->hasRole('manager')) {
            $this->redirectTo = route('academic.users.index');
            return $this->redirectTo;
        } else {
            $this->redirectTo = route('member.index');
            return $this->redirectTo;
        }
    }

    protected function logout()
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        return redirect()->route('home')->withCookie(cookie('XSRF-TOKEN', ''));
    }
    public function username()
    {
        return 'username';
    }
}
