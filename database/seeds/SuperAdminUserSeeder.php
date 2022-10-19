<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@egbin-power.com',
            'password' => 'h*f7492Lf37w0Cj',
            'api_pass' => 'h*f7492Lf37w0Cj',
        ]);
    }
}
