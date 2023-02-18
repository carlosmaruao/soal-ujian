<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // $pilKelas = [
        //     [
        //         "nmKelas" => 1
        //     ],
        //     ];
            
        Gate::define('admin-area', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('member-area', function ($user) {
            return $user->hasRole('member');
        });

        Gate::define('academic-area', function ($user) {
            return $user->hasRole('manager');
        });

        // Gate::define('user-area', function ($user) {
        //     return $user->hasRole('user');
        // });
    }
}
