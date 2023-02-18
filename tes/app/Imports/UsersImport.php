<?php

namespace App\Imports;

use App\Models\Admin\Role;
use App\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements
    ToCollection,
    WithValidation,
    WithHeadingRow
// SkipsOnFailure
// SkipsOnError
{
    use Importable;
    // SkipsFailures;
    // SkipsErrors;

    public function collection(Collection $rows)
    {
        // Validator::make($rows->toArray(), [
        //     // '*.0' => 'required',
        //     '*.nim' => ['required', 'unique:users,username'],
        //     '*.email' => ['email', 'unique:users,email']
        // ])->validate();

        foreach ($rows as $row) {
            $user = User::create(
                [
                    'name' => $row['nama'],
                    'username' => $row['nim'], 
                    'email' => $row['nim'].'@gmail.com',
                    'kelas' => $row['kelas'],
                    'password' => Hash::make($row['nim']),
                    'email_verified_at' => Carbon::now()->toDateTimeString(),
                    'thumbnail' => 'images/foto/profile/'.$row['nim'].'.jpg'
                ]
            );

            $role = Role::select('id')->where('name', 'member')->first();
            $user->roles()->attach($role);
        }
    }
    public function rules(): array
    {
        return [
            '*.nim' => ['required', 'unique:users,username'],
            // '*.email' => ['email', 'unique:users,email']
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nim.required' => 'Custom message for :attribute.',
        ];
    }
    public function prepareForValidation($data, $index)
    {
        $data['nim'] = $data['nim'] ?? 'demo';

        return $data;
    }
}
