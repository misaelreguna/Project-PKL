<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'misaelreguna',
                'email'=> 'misaelreguna@gmail.com',
                'role' => 'user',
                'password' =>bcrypt(123),
            ],
            [
                'name' => 'klei',
                'email'=> 'klei@gmail.com',
                'role' => 'admin',
                'password' =>bcrypt(123),
            ],
            [
                'name' => 'misael',
                'email'=> 'misael@gmail.com',
                'role' => 'bank',
                'password' =>bcrypt(123),
            ]
        ];
        foreach($user as $item){
            User::create($item);
        }
    }
}
