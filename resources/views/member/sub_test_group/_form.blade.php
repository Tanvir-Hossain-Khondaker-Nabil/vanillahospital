<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

 if (isset($modal)) {
    $project_image = $modal->image != '' ? $modal->project_image_path : '';
} else {
    $project_image = '';
}
// dd($project_image);
?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush


<div class="col-md-6">



    <div class="form-group {{ $errors->has('test_group_id') ? 'has-error' : '' }}">
        <label for="client">Test Group <span class="text-red"> * </span></label>
        {!! Form::select('test_group_id', $test_groups, null, [
            'id' => 'test_group_id',
            'placeholder' => 'Select Test Group',
            'class' => 'form-control select2',
            'required',
        ]) !!}
    </div>


    <div class="form-group">
        <label for="name">Sub Test Title <span class="text-red"> * </span> </label>
        {!! Form::text('title', null, [
            'id' => 'title',
            'class' => 'form-control',
            'placeholder' => 'Enter Sub Test Title',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Price <span class="text-red"> * </span> </label>
        {!! Form::text('price', null, [
            'id' => 'price',
            'class' => 'form-control',
            'placeholder' => 'Enter price',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Quack Ref Com </label>
        {!! Form::text('quack_ref_com', null, [
            'id' => 'quack_ref_com',
            'class' => 'form-control',
            'placeholder' => 'Enter Quack Ref Com',

        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Ref Val </label>
        {!! Form::text('ref_val', null, [
            'id' => 'ref_val',
            'class' => 'form-control',
            'placeholder' => 'Enter Ref Val',

        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Unit  </label>
        {!! Form::text('unit', null, [
            'id' => 'unit',
            'class' => 'form-control',
            'placeholder' => 'Enter unit',

        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Room No </label>
        {!! Form::text('room_no', null, [
            'id' => 'room_no',
            'class' => 'form-control',
            'placeholder' => 'Enter Room No',

        ]) !!}
    </div>

</div>



@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/adminLTE/plugins/fileupload/js/dropify.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();
        $(function() {

            $('.select2').select2();
            $('.dropify').dropify();
        });

        $(function() {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('description', {
                toolbar: 'MA'
            });
        });
    </script>
@endpush
