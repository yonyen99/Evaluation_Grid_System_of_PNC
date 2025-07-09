<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run():void
    {
        $now = Carbon::now();

        /**
         * Users Data
         * @var array
         */
        $users = [
            [
                'username'  => env('ADMIN_NAME'),
                'firstname' => 'admin',
                'lastname'  => 'admin',
                'email'     => env('ADMIN_EMAIL'),
                'email_verified_at' => $now,
                'password'  => Hash::make(env('ADMIN_PASSWORD')),
            ],
        ];

        foreach($users as $user){
            User::create($user);
        }

    }

    
    /**
     * Assing role and permission for current object user.
     * 
     * @param ObjectModel $user
     * @param String $role_slug
     * @param Arr_String $permission_slugArr
     * 
     * @return void
     */
    protected function userRolePermissionSeeder($user, $role_slug, $permission_slugArr){
        // get role and attach with user
        try {
            $roleId = Role::where('slug', $role_slug)->pluck('id');
            $user->roles()->attach($roleId);

        } catch(ModelNotFoundException $ex) {
            Log::error($ex);
        } 

        // get permission and attach with user
        try {
            foreach($permission_slugArr as $permission_slug) {
                $permissionId = Permission::where('slug', $permission_slug)->pluck('id');
                $user->permissions()->attach($permissionId);
            }

        } catch (ModelNotFoundException $ex) {
            Log::error($ex);
        }
    }

}
