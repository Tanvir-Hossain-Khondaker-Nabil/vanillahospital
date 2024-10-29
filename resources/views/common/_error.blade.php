<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/11/2019
 * Time: 10:49 AM
 */


?>


@if(Session::has('validation_errors'))
    <div class="alert alert-danger">
        <ul>
            @foreach (Session::get('validation_errors')->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    {{ Session::forget('validation_errors') }} <!-- Clear the validation errors from the session after displaying them -->

@else
    @if(!is_array($errors) && $errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif
