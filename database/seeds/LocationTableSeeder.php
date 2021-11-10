<?php

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $province = json_decode(file_get_contents('https://d.kapanlaginetwork.com/banner/test/province.json'), true);
        $data = [];
        foreach ($province as $item)
        {
            $data[] = [
                'kode' => $item['kode'],
                'nama' => $item['nama'],
                'type' => 'province',
            ];
        }

        Location::insert($data);

        $city = json_decode(file_get_contents('https://d.kapanlaginetwork.com/banner/test/city.json'), true);
        $data = [];
        foreach ($city as $item)
        {
            $data[] = [
                'kode' => $item['kode'],
                'nama' => $item['nama'],
                'type' => 'city',
            ];
        }

        Location::insert($data);
    }
}
