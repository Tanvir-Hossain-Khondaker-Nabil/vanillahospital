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


    <div class="form-group {{ $errors->has('marketing_officer_id') ? 'has-error' : '' }}">
        <label for="client">Client</label>
        {!! Form::select('marketing_officer_id', [], null, [
            'id' => 'marketing_officer_id',
            'placeholder' => 'Select Officer',
            'class' => 'form-control select2',

        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Name <span class="text-red"> * </span> </label>
        {!! Form::text('name', null, [
            'id' => 'name',
            'class' => 'form-control',
            'placeholder' => 'Enter name',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Details <span class="text-red"> * </span> </label>
        {!! Form::text('degree', null, [
            'id' => 'degree',
            'class' => 'form-control',
            'placeholder' => 'Details (M.B.B.S,FCPS etc)',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Consult Fee </label>
        {!! Form::text('consult_fee', null, [
            'id' => 'consult_fee',
            'class' => 'form-control',
            'type' => 'number',
            'placeholder' => 'Doctor Consult Fee',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Doctor Fee (Old Patient) </label>
        {!! Form::text('fee_old_patient', null, [
            'id' => 'fee_old_patient',
            'class' => 'form-control',
            'type' => 'number',
            'placeholder' => 'Doctor Fee (Old Patient)',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Doctor Fee (Only Report) </label>
        {!! Form::text('fee_only_report', null, [
            'id' => 'fee_only_report',
            'class' => 'form-control',
            'type' => 'number',
            'placeholder' => 'Doctor Fee (Only Report)',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Mobile <span class="text-red"> * </span> </label>
        {!! Form::text('mobile', null, [
            'id' => 'mobile',
            'class' => 'form-control',
            'placeholder' => 'Mobile',
            'required',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="name">Address </label>
        {!! Form::text('address', null, [
            'id' => 'address',
            'class' => 'form-control',
            'placeholder' => 'Address',

        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
        <label for="lead_category_id">Doctor Type <span class="text-red"> * </span></label>
        <select class="form-control select2" name="type">
               <option value="" >Select Type</option>
               <option {{@$model->type == 'MBBS'? "selected" : "" }} value="MBBS">MBBS</option>
               <option {{@$model->type == 'QUACK'? "selected" : "" }} value="QUACK">QUACK</option>
        </select>
    </div>

    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
        <label for="lead_category_id">Doctor Image <span class="text-red"> * </span></label>
        <div>
            @if (@$model)
            <input type="file" data-default-file="{{asset('/public/uploads/doctor/'.$model->image)}}"
            name="image" class="dropify" data-max-file-size="1M" data-height="200"
            data-allowed-file-extensions="jpg jpeg png"  accept="image/png, image/jpg, image/jpeg" />
            @else
            <input type="file"
            name="image" class="dropify" data-max-file-size="1M" data-height="200" data-allowed-file-extensions="jpg jpeg png"  accept="image/png, image/jpg, image/jpeg" />
            @endif

        </div>
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
