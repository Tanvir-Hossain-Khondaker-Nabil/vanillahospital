<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/19/2019
 * Time: 4:40 PM
 */

namespace App\Http\Traits;


trait DefaultEmailName
{
    public function defaultEmailPerson($data = [])
    {
        $data['name'] = "Mobarok Hossen";
        $data['email'] = "mobarokaiub@gmail.com";

        return $data;
    }
}
