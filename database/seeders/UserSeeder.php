<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::findByName('admin');
        $managerRole = Role::findByName('manager');
        $userRole = Role::findByName('user');


        $admin = User::factory()->create();
        $admin->assignRole($adminRole);


        $managers = User::factory(3)->create();
        $managers->each(function ($user) use ($managerRole) {
            $user->assignRole($managerRole);
        });

        User::factory(10)->create()->each(function ($user) use ($userRole, $managers) {
            $user->assignRole($userRole);
            $user->update(['manager_id' => $managers->random()->id]); // Setiap user punya manager
        });
    }
}
