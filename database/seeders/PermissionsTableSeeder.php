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

        // Dashborad Access Permissions Seeder [BEGIN]
            Permission::updateOrCreate(['name' => 'access dashboard']);
        // Dashborad Access Permissions Seeder [END]

        // give permissions to role
            $adminRole = Role::where('name', 'admin')->get()->first();
            $adminRole->givePermissionTo(Permission::all());

            $adminUser = User::where('email', env('ADMIN_EMAIL'))->get()->first();
            $adminUser->assignRole('admin');
    }
}
