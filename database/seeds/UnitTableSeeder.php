<?php

use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'KG' => 'kg',
            'GM' => 'gm',
            'Ton' => 'ton',
            'Bags' => 'bags',
            'Piece' => 'piece',
        ];

        $units = array(
            array('id' => '1','name' => 'kg','slug' => 'kg','created_by' => '1','updated_by' => NULL,'created_at' => '2022-03-29 02:11:41','updated_at' => '2022-03-29 02:11:41','display_name' => 'KG','deleted_at' => NULL),
            array('id' => '2','name' => 'gm','slug' => 'gm','created_by' => '1','updated_by' => NULL,'created_at' => '2022-03-29 02:11:41','updated_at' => '2022-03-29 02:11:41','display_name' => 'GM','deleted_at' => NULL),
            array('id' => '3','name' => 'ton','slug' => 'ton','created_by' => '1','updated_by' => NULL,'created_at' => '2022-03-29 02:11:41','updated_at' => '2022-03-29 02:11:41','display_name' => 'Ton','deleted_at' => NULL),
            array('id' => '4','name' => 'bags','slug' => 'bags','created_by' => '1','updated_by' => NULL,'created_at' => '2022-03-29 02:11:41','updated_at' => '2022-03-29 02:11:41','display_name' => 'Bags','deleted_at' => NULL),
            array('id' => '6','name' => 'Pcs','slug' => 'pcs','created_by' => '1','updated_by' => NULL,'created_at' => '2022-04-09 07:26:53','updated_at' => '2022-04-09 07:26:53','display_name' => 'Pcs','deleted_at' => NULL),
            array('id' => '8','name' => 'Carton','slug' => 'carton','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-18 00:17:39','updated_at' => '2022-10-18 00:17:39','display_name' => 'Carton','deleted_at' => NULL),
            array('id' => '9','name' => 'Meter / Yds','slug' => 'meter/_yds','created_by' => '1','updated_by' => '1','created_at' => '2022-10-19 01:00:43','updated_at' => '2022-10-19 01:07:44','display_name' => 'Meter / Yds','deleted_at' => NULL),
            array('id' => '10','name' => 'pair','slug' => 'pair','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-21 03:14:03','updated_at' => '2022-10-21 03:14:03','display_name' => 'Pair','deleted_at' => NULL),
            array('id' => '11','name' => 'Box','slug' => 'box','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-22 21:55:14','updated_at' => '2022-10-22 21:55:14','display_name' => 'Box','deleted_at' => NULL),
            array('id' => '12','name' => 'ft','slug' => 'ft','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-22 21:55:26','updated_at' => '2022-10-22 21:55:26','display_name' => 'Ft','deleted_at' => NULL),
            array('id' => '13','name' => 'cm','slug' => 'cm','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-22 21:55:37','updated_at' => '2022-10-22 21:55:37','display_name' => 'Cm','deleted_at' => NULL),
            array('id' => '14','name' => 'mm','slug' => 'mm','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-22 21:55:43','updated_at' => '2022-10-22 21:55:43','display_name' => 'Mm','deleted_at' => NULL),
            array('id' => '15','name' => 'inch','slug' => 'inch','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-22 21:55:51','updated_at' => '2022-10-22 21:55:51','display_name' => 'Inch','deleted_at' => NULL),
            array('id' => '16','name' => 'Litter','slug' => 'litter','created_by' => '1','updated_by' => NULL,'created_at' => '2022-10-22 21:58:30','updated_at' => '2022-10-22 21:58:30','display_name' => 'Litter','deleted_at' => NULL)
        );



        foreach ($units as $key => $item) {
            \App\Models\Unit::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'display_name' => $item['display_name'],
                'created_by' => 1
            ]);

        }

    }
}
