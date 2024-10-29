<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:55 PM
 */
?>


@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush


<div class="col-md-7">

    <div class="form-group  {{ $errors->has('display_name') ? 'has-error' : '' }} ">
        <label for="name">{{__('common.name')}} <span class="text-red"> * </span> </label>
        {!! Form::text('display_name', null, [
            'id' => 'display_name',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_name'),
            'required',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
        <label for="parent_id">{{__('category.select_parent_category')}} </label>
        {!! Form::select('parent_id', $categories, null, [
            'id' => 'parent_id',
            'class' => 'form-control select2',
            'placeholder' => trans('category.please_select_parent_category'),
        ]) !!}
    </div>
    <div class="form-group">
        <label for=file> {{__('category.category_image')}} </label>
        {{-- <input class="form-control" name="category_image" type="file" accept='image/*' /> --}}

        <input type="file" id='' class="form-control" accept="image/*" name="category_image"
            placeholder="Import image" onchange="getImagePreview(this)">
        <input type="hidden" id="front-image-url" value="">
        <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
        </div>
    </div>
    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">{{__("common.select_status")}} </label>
        {!! Form::select('status', ['active' => trans('common.active'), 'inactive' => trans('common.inactive')], null, [
            'id' => 'status',
            'class' => 'form-control',
        ]) !!}
    </div>
</div>



@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();
        $(function() {

            $('.select2').select2();

        });
    </script>
@endpush
