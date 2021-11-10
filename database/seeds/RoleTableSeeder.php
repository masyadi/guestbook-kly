<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'status' => '1',
        ]);

        \DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Admin',
            'slug' => 'admin',
            'status' => '1',
        ]);
    }
}
