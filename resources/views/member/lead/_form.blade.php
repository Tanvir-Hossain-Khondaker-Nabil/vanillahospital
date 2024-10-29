

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

    <div class="form-group {{ $errors->has('client') ? 'has-error' : '' }}">
        <label for="client">Client <span class="text-red"> * </span></label>
        {!! Form::select('client_id', $client, null, [
            'id' => 'client_id',
            'placeholder' => 'Select Client',
            'class' => 'form-control select2',
            'required',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
        <label for="company">Company <span class="text-red"> * </span></label>
        {!! Form::select('lead_company_id', $company, null, [
            'id' => 'lead_company_id',
            'placeholder' => 'Select Company',
            'class' => 'form-control select2',
            'required',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('lead_category') ? 'has-error' : '' }}">
        <label for="lead_category_id">Lead Category <span class="text-red"> * </span></label>
        {!! Form::select('lead_category_id', $lead_category, null, [
            'id' => 'lead_category_id',
            'placeholder' => 'Select Lead Category',
            'class' => 'form-control select2',
            'required',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('lead_status') ? 'has-error' : '' }}">
        <label for="lead_status">Status <span class="text-red"> * </span></label>
        {!! Form::select(
            'lead_status',$lead_status,
            isset($model) ? $model->leadStatus->last()->lead_status : null,
            ['id' => 'lead_status', 'placeholder' => 'Select Status', 'class' => 'form-control','required'],
        ) !!}
    </div>

    <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
        <label for="label">Label </label>
        {{ Form::select('label_id[]', $label, $labeling ?? null, ['class' => 'select2 form-control', 'multiple' => 'multiple']) }}
    </div>
</div>



@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();
        $(function() {

            $('.select2').select2();

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
