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
        <h1>New Hisebi System Created</h1>
        <h4>Registration successfully completed. </h4>

        <h4> Company Name: {{ $name }}</h4>
        <h4> Login Email: {{ $email }}</h4>
        <h4> Login Password: {{ $password }}</h4>
        <a href="{{ route('login') }}" target="_blank" class="btn btn-success" >
            Click to Login
        </a>
    </div>
</div>

