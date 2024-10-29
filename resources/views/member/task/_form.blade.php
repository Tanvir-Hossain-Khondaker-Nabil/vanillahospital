<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

if (isset($model)) {
    $task_image = $model->image != '' ? $model->task_image_path : '';
} else {
    $task_image = '';
}
?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

{{-- {{dd($model->labeling->)}} --}}
<div class="col-md-6">
    <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }} ">
        <label for="project">Title <span class="text-red"> * </span> </label>
        {!! Form::text('title', null, [
            'id' => 'title',
            'class' => 'form-control',
            'placeholder' => 'Enter Title',
            'required',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('project') ? 'has-error' : '' }}">
        <label for="status">Project <span class="text-red"> * </span></label>

        {!! Form::select('project_id', $project, $project_id?? null, [
            'id' => 'project_id',
            'placeholder' => 'Select Project',
            'class' => 'form-control select2',
            'required',
        ]) !!}
    </div>
    {{-- {{dd($employee)}} --}}

    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">Assign To Employee<span class="text-red"> * </span></label>
        {!! Form::select('employee_info_id[]', $employees , $task_employees ?? null,['id'=>'employee_id','class'=>'form-control select2','multiple'=>'multiple','required',]); !!}
    </div>



    <div class="form-group  {{ $errors->has('start_date') ? 'has-error' : '' }} ">
        <label for="expire_date">Start Date <span class="text-red"> * </span></label>
        {!! Form::date('start_date', null, [
            'id' => 'start_date',
            'class' => 'form-control',
            'placeholder' => 'Enter Start Date',
            'required',
        ]) !!}
    </div>

    <div class="form-group  {{ $errors->has('start_date') ? 'has-error' : '' }} ">
        <label for="expire_date">Deadline</label>
        {!! Form::date('end_date', null, [
            'id' => 'end_date',
            'class' => 'form-control',
            'placeholder' => 'Enter Deadline',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('priority') ? 'has-error' : '' }}">
        <label for="status">Priority </label>
        {!! Form::select('priority', ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'], null, [
            'id' => 'priority',
            'placeholder' => 'Select Priority',
            'class' => 'form-control',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
        <label for="label">Label </label>
        {{ Form::select('label_id[]', $label, $labeling ?? null, ['class' => 'select2 form-control', 'multiple' => 'multiple']) }}
    </div>

    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">Status <span class="text-red"> * </span></label>
        {!! Form::select('status', $statuses ,null,
            ['id' => 'status', 'placeholder' => 'Select Status', 'class' => 'form-control', 'required']
        ) !!}
    </div>

    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
        <label for="image">Image </label>
        {{-- {!! Form::file('image', null, [
            'id' => 'image',
            'accept' => 'image/*',
            'placeholder' => 'Import image',
            'class' => 'form-control',
        ]) !!} --}}

        <input type="file" id='image' class="form-control" accept="image/*" name="image"
        placeholder="Enter Image" onchange="getImagePreview(this)">
    <input type="hidden" id="front-image-url" value="">
    <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap"></div>

        @if ($task_image)
            <img class="mt-2" src="{{ $task_image }}" style="max-height: 70px; width: 70px !important;"
                 alt=""> <br/>
        @endif
    </div>


</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="description">Description </label>
        {!! Form::textarea('description', null, [
            'id' => 'description',
            'class' => 'form-control',
            'placeholder' => 'Enter Description'
        ]) !!}
    </div>

</div>

@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();
        $(function () {

            $('.select2').select2();

        });

        $(function () {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('description', {
                toolbar: 'MA'
            });
        });
    </script>
@endpush
