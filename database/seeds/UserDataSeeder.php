<?php

use Illuminate\Database\Seeder;
use App\User;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'email' => 'user@admin.com',
            'password' => bcrypt('password')
        ];
        $admin = User::create($data);
    }
}
