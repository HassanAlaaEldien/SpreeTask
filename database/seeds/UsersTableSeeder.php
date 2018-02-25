<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        $superAdmin = User::create([
            'name' => 'superAdmin',
            'email' => 'superAdmin@spree.com',
            'password' => bcrypt('secret')
        ]);
        $superAdmin->roles()->attach($superAdminRole);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@spree.com',
            'password' => bcrypt('secret')
        ]);
        $admin->roles()->attach($adminRole);

        $user = User::create([
            'name' => 'user',
            'email' => 'user@spree.com',
            'password' => bcrypt('secret')
        ]);
        $user->roles()->attach($userRole);

    }
}
