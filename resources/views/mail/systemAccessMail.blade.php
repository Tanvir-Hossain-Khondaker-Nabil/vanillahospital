<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/12/2019
 * Time: 3:02 PM
 */
?>

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">


<div style=" text-align:left; width: 700px; min-height: 500px; background-repeat: no-repeat; background-size: 100%;">
    <div style="color: #000; padding: 10px 40px; font-family: Roboto;">
        <h1>{{  human_words(config('app.name')) }}</h1>
        <h1>Thanks for System Registration</h1>
        <h4>Your registration successfully completed. </h4>

        <h4>Your Company Name: {{ $name }}</h4>
        <h4>Your Login Email: {{ $email }}</h4>
        <h4>Your Login Password: {{ $password }}</h4>
        <a href="{{ route('login') }}" target="_blank" class="btn btn-success" >
            Click to Login
        </a>
    </div>
</div>
