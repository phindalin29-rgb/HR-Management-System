<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Full module vocabulary used by the `module` route middleware.
        $all = [
            'dashboard', 'employees', 'departments', 'designations', 'timesheet', 'overtime',
            'attendance', 'leave', 'holidays', 'payroll', 'performance', 'training', 'recruitment',
            'calendar', 'notifications', 'audit-log', 'settings', 'users', 'profile', 'reports',
            'assets', 'sales', 'expenses', 'chat', 'localization', 'ess', 'api',
        ];

        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'គ្រប់គ្រងប្រព័ន្ធទាំងមូល',
                'modules' => $all,
                'is_system' => true,
            ],
            [
                'name' => 'HR Manager',
                'slug' => 'hr-manager',
                'description' => 'គ្រប់គ្រង HR Module ទាំងអស់',
                'modules' => $all,
                'is_system' => true,
            ],
            [
                'name' => 'HR Officer',
                'slug' => 'hr-officer',
                'description' => 'គ្រប់គ្រងបុគ្គលិក, Leave, Training, Recruitment',
                'modules' => [
                    'dashboard', 'employees', 'departments', 'designations', 'timesheet', 'overtime',
                    'attendance', 'leave', 'holidays', 'training', 'calendar', 'profile', 'ess',
                    'notifications', 'recruitment', 'reports', 'chat',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Payroll Officer',
                'slug' => 'payroll-officer',
                'description' => 'Salary, Payroll, Expenses, Assets',
                'modules' => [
                    'dashboard', 'payroll', 'employees', 'reports', 'expenses', 'assets',
                    'profile', 'ess', 'chat',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Department Manager',
                'slug' => 'department-manager',
                'description' => 'អនុម័ត Leave និងគ្រប់គ្រងបុគ្គលិកក្នុងផ្នែក',
                'modules' => [
                    'dashboard', 'employees', 'departments', 'attendance', 'leave', 'calendar',
                    'holidays', 'profile', 'ess', 'chat',
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'មើលព័ត៌មានផ្ទាល់ខ្លួន និងស្នើសុំ Leave',
                'modules' => [
                    'dashboard', 'ess', 'attendance', 'leave', 'calendar', 'holidays',
                    'recruitment', 'profile', 'chat',
                ],
                'is_system' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
