<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/4/2020
 * Time: 1:12 PM
 */
?>

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

<div style="background-color: #e2e2e2; text-align:center; width: 700px; min-height: 500px; background-repeat: no-repeat; background-size: 100%;">
    <div style="color: #000; padding: 100px 40px 70px; font-family: Roboto;">
        <h1>{{  human_words(config('app.name')) }}</h1>
        <h1>Contact From Using Hisebi</h1>
        <h4>Name: {{ $name }}</h4>
        <h4>Email: {{ $email }}</h4>
        <h4>Phone: {{ $phone }}</h4>
        <h4>Company Name: {{ $company_name }}</h4>
        <h4>Subject: {{ $subject }}</h4>
        <h4>Message: {{ $message }}</h4>

    </div>
</div>

