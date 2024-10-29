<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 16-Feb-20
 * Time: 5:28 PM
 */

return [

    /*
   |--------------------------------------------------------------------------
   | Company Profile Setup
   |--------------------------------------------------------------------------
   |
   | This value is the name of your application. This value is used when the
   | framework needs to place the application's name in a notification or
   | any other location as required by the application or its packages.
   |
   */

    'feature' => true,


    'name' => env('COMPANY_NAME', 'Laravel'),
    'logo' => env('COMPANY_LOGO', ''),
    'email' => env('COMPANY_EMAIL', 'info@laravel.com'),
    'address' => env('COMPANY_ADDRESS', 'Muradpur'),
    'phone' => env('COMPANY_PHONE', '018XXXXXXXX'),
    'city' => env('CITY', 'Chattogram'),
    'country' => env('COUNTRY', 'Bangladesh'),

];
