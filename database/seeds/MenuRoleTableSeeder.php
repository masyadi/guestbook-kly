<?php

use Illuminate\Database\Seeder;

class MenuRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('menus')->insert([
            'name' => 'Dashboard',
            'slug' => '/',
            'icon' => 'fa fa-home',
            'show_all' => '1',
            'parent' => 0,
            'order' => 1,
        ]);

        //UPDATE
        if( $menuID = \DB::table('menus')->insertGetId([
            'name' => 'Update',
            'slug' => '#',
            'icon' => 'fa fa-pencil',
            'parent' => 0,
            'order' => 2,
        ]) )
        {
            //sub menu
            \DB::table('menus')->insert([
                [
                    'name' => 'Guest Book',
                    'slug' => 'guest-book',
                    'icon' => 'fa fa-circle-o',
                    'parent' => $menuID,
                    'order' => 1
                ],
            ]);
        }

        //SYSTEM
        if( $menuID = \DB::table('menus')->insertGetId([
            'name' => 'System',
            'slug' => '#',
            'icon' => 'fa fa-gear',
            'parent' => 0,
            'order' => 7,
        ]) )
        {
            //sub menu
            \DB::table('menus')->insert([
                [
                    'name' => 'Role',
                    'slug' => 'role',
                    'icon' => 'fa fa-circle-o',
                    'parent' => $menuID,
                    'order' => 1
                ],
                [
                    'name' => 'Menu',
                    'slug' => 'menu',
                    'icon' => 'fa fa-circle-o',
                    'parent' => $menuID,
                    'order' => 2
                ],
                [
                    'name' => 'User',
                    'slug' => 'user',
                    'icon' => 'fa fa-circle-o',
                    'parent' => $menuID,
                    'order' => 4
                ]
            ]);
        }

        //GRANT ACCESS SUPER ADMIN
        if( $rows = \DB::table('menus')->where('show_all', '0')->get() )
        {
            $dtAccess = [];

            foreach( $rows as $r )
            {
                for( $i=1; $i<=4; $i++ )
                {
                    $dtAccess[] = ['role_id'=>$i, 'menu_id'=>$r->id, 'created_at'=>date("Y-m-d H:i:s")];
                }
            }

            \DB::table('role_menus')->insert($dtAccess);
        }
    }
}
