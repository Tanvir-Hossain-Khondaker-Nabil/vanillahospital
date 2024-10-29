<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 10/22/2022
 * Time: 12:03 PM
 */

namespace App\Services;


interface Installer
{
    public function install($filename);
    public function convertOptions();
    public function put($key, $value);
}
