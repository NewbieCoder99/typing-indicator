<?php

use Illuminate\Database\Seeder;
use App\User;

class createUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
        	[
        		'name' => 'user 1',
        		'email' => 'user1@gmail.com',
        		'password' => \Hash::make('password'),
        	],
        	[
        		'name' => 'user 2',
        		'email' => 'user2@gmail.com',
        		'password' => \Hash::make('password'),
        	]
        ]);
    }
}
