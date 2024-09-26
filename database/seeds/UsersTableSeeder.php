<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => Hash::make('admin@admin.com'),
                'remember_token' => null,
                'number' => '1234567890',
                'city' => 'xyz',
                'company' => 'abc',
                'designation' => 'admin'
            ],
            [
                'id'             => 2,
                'name'           => 'Agent 1',
                'email'          => 'agent1@agent1.com',
                'password'       => Hash::make('agent1@agent1.com'),
                'remember_token' => null,
                'number' => '1234567890',
                'city' => 'xyz',
                'company' => 'abc',
                'designation' => 'agent'
            ],
            [
                'id'             => 3,
                'name'           => 'Agent 2',
                'email'          => 'agent2@agent2.com',
                'password'       =>  Hash::make('agent2@agent2.com'),
                'remember_token' => null,
                'number' => '1234567890',
                'city' => 'xyz',
                'company' => 'abc',
                'designation' => 'agent'
            ],
            [
                'id'             => 4,
                'name'           => 'Agent 3',
                'email'          => 'agent3@agent3.com',
                'password'       =>  Hash::make('agent3@agent3.com'),
                'remember_token' => null,
                'number' => '1234567890',
                'city' => 'xyz',
                'company' => 'abc',
                'designation' => 'agent'
            ],
        ];

        User::insert($users);
    }
}
