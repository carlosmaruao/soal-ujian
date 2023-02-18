<?php

use App\Models\Admin\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('role_user')->truncate();

        $adminRole = Role::where('name', 'admin')->first();
        $academicRole = Role::where('name', 'manager')->first();

        $passAdmin = 'Admin!#';
        $passAcademic = 'Academic!#';

        $admin = User::create([
            'name' => 'Admin LSPR',
            'username' => 'admin',
            'password' => bcrypt($passAdmin),
            'email_verified_at' => Carbon::now(),
            'email' => $passAdmin . '@gmail.com',
            'thumbnail' => 'images/foto/profile/default.jpg'
        ]);
        $academic = User::create([
            'name' => 'Academic',
            'username' => 'academic',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt($passAcademic),
            'email' => $passAcademic.'@gmail.com',
            'thumbnail' => 'images/foto/profile/default.jpg'
        ]);

        $admin->roles()->attach($adminRole);
        $academic->roles()->attach($academicRole);
    }
}
