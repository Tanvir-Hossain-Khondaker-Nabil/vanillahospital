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
        <label for="name">Name <span class="text-red"> * </span> </label>
        {!! Form::text('name', null, [
            'id' => 'name',
            'class' => 'form-control',
            'placeholder' => 'Enter Name',
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="designation">Designation <span class="text-red"> * </span> </label>
        {!! Form::text('designation', null, [
            'id' => 'designation',
            'class' => 'form-control',
            'placeholder' => 'Enter designation',
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="contact_no">Contact Number <span class="text-red"> * </span> </label>
        {!! Form::text('contact_no', null, [
            'id' => 'contact_no',
            'class' => 'form-control',
            'placeholder' => 'Enter Contact Number',
            'required',
        ]) !!}
    </div>


    <div class="form-group">
        <label for="name">Description  </label>
        {!! Form::textarea('description', null, [
            'id' => 'description',
            'class' => 'form-control',
            'placeholder' => 'Enter description',
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="name">Address  </label>
        {!! Form::textarea('address', null, [
            'id' => 'address',
            'class' => 'form-control',
            'placeholder' => 'Enter address',
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="name">Image <span class="text-red"> * </span> </label>
        {!! Form::file('image', null, [
            'id' => 'image',
            'class' => 'form-control',
            'placeholder' => 'Enter image',
            'required',
        ]) !!}
    </div>
    @isset($model)
    <div class="form-group">
        <label for="name">Status  </label>
        {!! Form::select('status',[1=>'Active',0=>"InActive"],$model->status, [
            'id' => 'status',
            'class' => 'form-control select2',
            'placeholder' => 'Enter status',
            'required',
        ]) !!}
    </div>
    @endisset


</div>


@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <!-- Date range picker -->

@endpush


