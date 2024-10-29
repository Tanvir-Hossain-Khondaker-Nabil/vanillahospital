<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/12/2019
 * Time: 3:02 PM
 */
?>

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">


<!--
    TODO: Verify Token Mail Modify
-->

<div style="background-color: #e2e2e2; text-align:center; width: 700px; min-height: 500px; background-repeat: no-repeat; background-size: 100%;">
    <div style="color: #000; padding: 100px 40px 70px; font-family: Roboto;">
        <h1>{{  human_words(config('app.name')) }}</h1>
        <h1>Thanks for Registration</h1>
        <h4>For Confirmation your Registration, please click below URL link:</h4>
        <a href="{{ $url }}" target="_blank" class="btn btn-success" > Confirm Registration </a>
    </div>
</div>
