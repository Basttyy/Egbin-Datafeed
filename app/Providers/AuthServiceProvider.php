<?php

namespace App\Providers;

use App\Metric;
use App\Role;
use App\User;
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

        Gate::define('approve-metric', function (User $user) {
            $role = $user->role()->first();
            return $role->role === Role::SUPER_ADMIN || $role->role === Role::ADMIN;
        });
        Gate::define('register-user', function (User $user) {
            $role = $user->role()->first();
            return $role->role === Role::SUPER_ADMIN || $role->role === Role::ADMIN;
        });
    }
}
