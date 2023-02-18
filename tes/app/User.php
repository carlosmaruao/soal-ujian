<?php

namespace App;

use App\Models\Admin\Post;
use App\Models\Admin\Role;
use App\Models\Members\Payment;
use App\Models\Members\Profile;
use App\Models\Members\Registration;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

// class User extends Authenticatable
class User extends Authenticatable
// class User extends Authenticatable implements MustVerifyEmail, JWTSubject  
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'kelas', 'telepon', 'email', 'password', 'email_verified_at', 'status', 'thumbnail', 'kelengkapan'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function infos()
    {
        return $this->hasMany(Info::class);
    }
    public function skors()
    {
        return $this->hasMany(Skor::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasAnyRoles($roles)
    {
        if ($this->roles()->whereIn('name', $roles)->first()) {
            return true;
        }

        return false;
    }

    public function isMember()
    {
        if (Auth::user()->status) {
            return true;
        }
        return false;
    }


    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }

        return false;
    }
    //Providers\AuthServiceProvider 



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
