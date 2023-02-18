<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use App\Mail\TestMail;
use App\Models\Admin\Role;
use App\Models\Admin\Province;
use App\Models\Members\Profile;
use App\Http\Controllers\Controller;
use App\Models\Members\Registration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 
        $this->middleware('guest'); 
    }

    /**
     * Get a validator for an incoming registration request.
     *\
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    { 
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'nmKelas' => ['required'],
            'telepon' => 'required|numeric',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'email' => $data['email'],
            'telepon' => $data['telepon'],
            'kelas' => $data['nmKelas'],
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make($data['telepon']),
            'thumbnail' => 'images/foto/profile/default.jpg'
        ]);
        // $modNumber = Registration::where('kode', $data['kode'])->max('urutan') + 1;
        // $formatBulan = Carbon::now()->format('m');
        // $bulan =  str_pad($formatBulan, 2, '0', STR_PAD_LEFT);
        // $tahun = Carbon::now()->format('y');
        // $fullCode = $data['kode'] . '' . $bulan . '' . $tahun . '' . str_pad($modNumber, 5, '0', STR_PAD_LEFT);

        // Registration::create([
        //     'member_id' => $fullCode,
        //     'user_id' => $user->id,
        //     // 'tanggal_bayar' => Carbon::now(),
        //     'kode' => $data['kode'],
        //     'urutan' => $modNumber,
        // ]);

        $role = Role::select('id')->where('name', 'member')->first();

        $user->roles()->attach($role);

        // // Profile::create([
        // //     'user_id' => $user->id,
        // // ]);

        // $details = [
        //     'title' => 'Registrasi Member Baru',
        //     'body' => 'Calon member a/n ' . $user->name,
        // ];
        // Mail::to(['rudy.onay@gmail.com', 'carlos.maruao@gmail.com'])->send(new TestMail($details));

        return $user;

        // $this->guard()->logout();
        // return redirect()->route('login');
    }

    protected function registered()
    { 
        $this->guard()->logout();
        return redirect()->route('login')->with('success', 'Terima kasih sudah registrasi, silahkan Login');
    }
}
