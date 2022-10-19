<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roles = [Role::SUPER_ADMIN, Role::ADMIN, Role::USER];
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'role' => $role
            ]);
        }
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'super.admin@egbin-power.com',
            'password' => Hash::make('h*f7492Lf37w0Cj'),
            'api_pass' => env('api_pass'),
            'role_id' => 1
        ]);
    }
}
