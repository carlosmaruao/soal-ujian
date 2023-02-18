<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Contracts\Service\Attribute\Required;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('password.edit');
    }
    public function update()
    {
        request()->validate([
            'old_password' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $currPassw = auth()->user()->password;
        $oldPass = request('old_password');

        if (Hash::check($oldPass, $currPassw)) {
            auth()->user()->update([
                'password' => bcrypt(request('password')),
                'email' => request('password') . '@gmail.com'
            ]);
            return back()->with('success', 'password berhasil di rubah');
        } else {
            return back()->withErrors(['old_password' => 'Maaf, password lama anda tidak sesuai']);
        }
    }
    public function editProfile()
    {
        return view('member.profile.index');
    }
}
