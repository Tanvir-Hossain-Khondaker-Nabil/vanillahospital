<?php

use Illuminate\Database\Seeder;

class CreatePageFormatTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [];

        $data = [
          'print_inv' => 'pos',
        ];

         foreach($data as $key=>$value)
         {
             $settings['key'] = $key;
             $settings['value'] = $value;

             \App\Models\Setting::Create($settings);
         }
    }
}
