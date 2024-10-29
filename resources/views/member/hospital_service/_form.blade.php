<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */
?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush


<div class="col-md-6">
    <div class="form-group">
        <label for="name">Title <span class="text-red"> * </span> </label>
        {!! Form::text('title', null, [
            'id' => 'title',
            'class' => 'form-control',
            'placeholder' => 'Enter Title',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Price <span class="text-red"> * </span> </label>
        {!! Form::text('price', null, [
            'id' => 'price',
            'class' => 'form-control',
            'placeholder' => 'Enter Price',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Comission <span class="text-red"> * </span> </label>
        {!! Form::text('comission', null, [
            'id' => 'comission',
            'class' => 'form-control',
            'placeholder' => 'Enter Comission',
            'required',
        ]) !!}
    </div>


</div>


@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <!-- Date range picker -->

@endpush


