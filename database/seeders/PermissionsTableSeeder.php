<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;


class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // System User Permissions Seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view system_user']);
        Permission::updateOrCreate(['name' => 'create system_user']);
        Permission::updateOrCreate(['name' => 'edit system_user']);
        Permission::updateOrCreate(['name' => 'delete system_user']);
        Permission::updateOrCreate(['name' => 'detail system_user']);
        // System User Permissions Seeder [END]

        // Role Permissions Seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view role']);
        Permission::updateOrCreate(['name' => 'create role']);
        Permission::updateOrCreate(['name' => 'edit role']);
        Permission::updateOrCreate(['name' => 'delete role']);
        // Role Permissions Seeder [END]

        // Rol permission seeder generation[BEGIN]
        Permission::updateOrCreate(['name' => 'view generation']);
        Permission::updateOrCreate(['name' => 'create generation']);
        Permission::updateOrCreate(['name' => 'edit generation']);
        Permission::updateOrCreate(['name' => 'delete generation']);
        // Rol permission seeder generation[END]

        // student Permission seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view student']);
        Permission::updateOrCreate(['name' => 'create student']);
        Permission::updateOrCreate(['name' => 'edit student']);
        Permission::updateOrCreate(['name' => 'delete student']);
        // student Permission seeder [END]

        // teacher Permission seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view teacher']);
        Permission::updateOrCreate(['name' => 'create teacher']);
        Permission::updateOrCreate(['name' => 'edit teacher']);
        Permission::updateOrCreate(['name' => 'delete teacher']);
        // teacher Permission seeder [END]

        // term Permission seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view term']);
        Permission::updateOrCreate(['name' => 'create term']);
        Permission::updateOrCreate(['name' => 'edit term']);
        Permission::updateOrCreate(['name' => 'delete term']);
        // term Permission seeder [END]

        // subject Permission seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view subject']);
        Permission::updateOrCreate(['name' => 'create subject']);
        Permission::updateOrCreate(['name' => 'edit subject']);
        Permission::updateOrCreate(['name' => 'delete subject']);
        // subject Permission seeder [END]

        // class Permission seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view class']);
        Permission::updateOrCreate(['name' => 'create class']);
        Permission::updateOrCreate(['name' => 'edit class']);
        Permission::updateOrCreate(['name' => 'delete class']);
        // class Permission seeder [END]

        // grid Permission seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view grid']);
        Permission::updateOrCreate(['name' => 'create grid']);
        Permission::updateOrCreate(['name' => 'edit grid']);
        Permission::updateOrCreate(['name' => 'delete grid']);
        // grid Permission seeder [END]

        // grid Permission seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view evaluation']);
        Permission::updateOrCreate(['name' => 'create evaluation']);

        // grid Permission seeder [END]

        // Log History Permissions Seeder [BEGIN]
        Permission::updateOrCreate(['name' => 'view loghistory']);
        // Log History Permissions Seeder [END]

        //Report permission seeder[BEGIN]
        Permission::updateOrCreate(['name' => 'view admin_report']);
        Permission::updateOrCreate(['name' => 'view teacher_report']);
        Permission::updateOrCreate(['name' => 'view student_report']);
        //Report permission seeder[END]

        // give permissions to role
        $adminRole = Role::where('name', 'admin')->get()->first();
        $adminermissions = Permission::whereNotIn('name', [
            'view teacher_report',
            'view student_report'
        ])->get();
        $adminRole->givePermissionTo($adminermissions);

        $adminUser = User::where('email', env('ADMIN_EMAIL'))->get()->first();
        $adminUser->assignRole('admin');

        // Get the Teacher role
        $teacherRole = Role::where('name', 'Teacher')->first();
        // Get all permissions except the ones you don’t want
        $teacherPermissions = Permission::whereNotIn('name', [
            'view system_user',
            'view role',
            'view admin_report',
            'view student_report'
        ])->get();

        // Give permissions to Teacher role
        $teacherRole->givePermissionTo($teacherPermissions);

        // Get the Student role
        $studentRole = Role::where('name', 'Student')->first();
        if ($studentRole) {
            // Give permissions to Student Role
            $studentRole->givePermissionTo([
                'view student_report'
            ]);
        }
    }
}
