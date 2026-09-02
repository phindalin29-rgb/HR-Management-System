<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates one demo login account per Khmer role so each function can be
 * accessed with an email + password. Idempotent (updateOrCreate by email).
 *
 *   hr@hr.local          / 12345678   (HR Manager)
 *   hrofficer@hr.local   / 12345678   (HR Officer)
 *   payroll@hr.local      / 12345678   (Payroll Officer)
 *   dept@hr.local         / 12345678   (Department Manager)
 *   employee@hr.local     / 12345678   (Employee)
 *
 * The Super Admin account (lin10@gmail.com) is created in DatabaseSeeder.
 */
class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $demos = [
            ['name' => 'HR Manager',       'email' => 'hr@hr.local',       'role' => 'hr-manager'],
            ['name' => 'HR Officer',       'email' => 'hrofficer@hr.local', 'role' => 'hr-officer'],
            ['name' => 'Payroll Officer',  'email' => 'payroll@hr.local',   'role' => 'payroll-officer'],
            ['name' => 'Department Manager','email' => 'dept@hr.local',      'role' => 'department-manager'],
            ['name' => 'Employee',         'email' => 'employee@hr.local',  'role' => 'employee'],
        ];

        foreach ($demos as $demo) {
            $role = Role::where('slug', $demo['role'])->first();

            if (!$role) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $demo['email']],
                [
                    'name'      => $demo['name'],
                    'role_id'   => $role->id,
                    'role_name' => $role->name,
                    'status'    => 'Active',
                    'password'  => Hash::make('12345678'),
                ]
            );
        }
    }
}
