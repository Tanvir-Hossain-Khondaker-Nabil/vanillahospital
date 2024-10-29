<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */



?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush


<div class="col-md-6">
    <div class="form-group">
        <label for="name">Title <span class="text-red"> * </span> </label>
        {!! Form::text('title',null,['id'=>'title','class'=>'form-control','placeholder'=>'Enter Title', 'required']); !!}
    </div>

    <div class="form-group">
        <div class="form-group">
            <label for="description">Message <span class="text-red"> * </span> </label>
            {!! Form::textarea('message',null,['id'=>'description','class'=>'form-control','placeholder'=>'Enter Description']); !!}
        </div>
    </div>

</div>
@push('scripts')

   <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
   <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {

    CKEDITOR.config.toolbar_MA=[ '/', ['Paragraph','list', 'indent', 'blocks', 'align', 'bidi','-', 'Format','Templates','Bold','Italic','-','NumberedList','BulletedList'] ];
    CKEDITOR.replace('description',
        {   toolbar:'MA'    });
    });
    </script>

@endpush

