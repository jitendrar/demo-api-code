<?php

use Illuminate\Database\Seeder;

class AdminUserTableSeeder extends Seeder
{
    public function run()
    {
    	$row = [
    		'first_name' => 'Admin',
    		'last_name' =>'User',
    		'email' => 'admin@vegetableapp.com',
    		'password' => bcrypt('123456'),
    	];

        DB::table('admin_user')->insert($row);
    }
}
