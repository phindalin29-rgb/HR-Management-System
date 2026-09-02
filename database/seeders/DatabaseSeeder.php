<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the Khmer roles first so users can be linked to them.
        $this->call(RoleSeeder::class);

        // Super Admin Account (idempotent so re-seeding is safe)
        $admin = User::firstOrCreate(
            ['email' => 'lin10@gmail.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('12345678')]
        );

        // Attach the Khmer "Super Admin" role so module permissions apply.
        if ($superRole = Role::where('slug', 'super-admin')->first()) {
            $admin->role_id = $superRole->id;
            $admin->role_name = $superRole->name;
            $admin->save();
        }

        // One demo login account per Khmer role (email + password).
        $this->call(DemoUsersSeeder::class);
    }
}
