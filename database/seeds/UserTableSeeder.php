<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'super.admin@gmail.com',
            'password' => \Hash::make(123456),
            'role_id' => 1,
        ]);

        \DB::table('users')->insert([
            'name' => 'Editor',
            'username' => 'editor',
            'email' => 'admin@email.com',
            'password' => \Hash::make(123456),
            'role_id' => 2,
        ]);

    }
}
