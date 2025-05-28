<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
         * 
         * @var array
         */
        $users = [
            [
                'name' => 'admin',
                'email'     => env('ADMIN_EMAIL'),
                'email_verified_at' => $now,
                'password'  => Hash::make(env('ADMIN_PASSWORD')),
            ],

        ];

        foreach($users as $user){
            User::create($user);
        }

    }

}
