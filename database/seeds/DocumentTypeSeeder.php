<?php

use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
          "Resume/CV",
          "National ID(NID)",
          "Bank Cheque",
          "Personal guarantee",
          "Trade License",
          "Passport",
          "Driving License",
          "Necessary documents of business",
          "Bank Statement",
          "Personal Residence(PR)",
          "Insurance ID",
          "Visa Copy",
        ];


        foreach ($array as $value)
        {
            $arr = [];
            $arr['name'] = $value;
            $arr['status'] = 'active';

            \App\Models\DocumentType::create($arr);
        }
    }
}
