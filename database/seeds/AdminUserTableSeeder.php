<?php

use Illuminate\Database\Seeder;
use App\AdminUser;
class AdminUserTableSeeder extends Seeder
{
    public function run()
    {
        $ArrUsers = array(
            [
                'id' => 1,
                'first_name' => 'Admin',
                'last_name' =>'User',
                'email' => 'admin@vegetableapp.com',
                'password' => bcrypt('Password@2'),
            ],
            [
                'id' => 2,
                'first_name' => 'Ashok',
                'last_name' =>'Sadhu',
                'email' => 'ashok.sadhu@phpdots.com',
                'password' => bcrypt('9067121123'),
            ]
        );
        foreach($ArrUsers as $user) {
            $isexist = AdminUser::find($user['id']);
            if (!$isexist) {
                AdminUser::Create($user);
            }
        }
    }
}
