<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */
?>

@push('styles')
    <link rel="stylesheet"
        href="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/dist/css/jasny-bootstrap.css') }}">
@endpush



<div class="col-md-6">
    <div class="form-group">
        <label for="name">Company Name<span class="text-red"> * </span> </label>
        {!! Form::text('company_name', null, [
            'id' => 'company_name',
            'class' => 'form-control',
            'placeholder' => 'Enter Company Name',
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="phone">Phone No<span class="text-red"> * </span> </label>
        {!! Form::text('phone', null, [
            'id' => 'phone',
            'class' => 'form-control',
            'placeholder' => 'Enter Phone No',
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="phone">Email<span class="text-red"> * </span> </label>
        {!! Form::text('email', null, [
            'id' => 'email',
            'class' => 'form-control ',
            'placeholder' => 'Enter Email',
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="display_name">Fiscal Year </label>
        {!! Form::select('fiscal_year_id', $fiscal_year, null, [
            'id' => 'fiscal_year_id',
            'class' => 'form-control select2',
            'placeholder' => 'Select Fiscal Year',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="display_name">Report Head sub title/text </label>
        {!! Form::textarea('report_head_sub_text', null, [
            'id' => 'description',
            'class' => 'form-control',
            'placeholder' => 'Enter Report Head Sub Content',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="tax">Tax </label>
        {!! Form::text('tax', null, ['class' => 'form-control ', 'placeholder' => 'Enter Tax']) !!}
    </div>
    <div class="form-group">
        <label for="phone">Quotation REF </label>
        {!! Form::text('quote_ref', null, ['class' => 'form-control ', 'placeholder' => 'Enter quote ref']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="display_name">Address <span class="text-red"> * </span> </label>
        {!! Form::textarea('address', null, [
            'id' => 'address',
            'class' => 'form-control',
            'placeholder' => 'Enter Address',
            'rows' => '2',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="city">City </label>
        {!! Form::text('city', null, ['id' => 'city', 'class' => 'form-control', 'placeholder' => 'Enter City']) !!}
    </div>
    <div class="form-group">
        <label for="display_name">Country <span class="text-red"> * </span> </label>
        {!! Form::select('country_id', $countries, null, [
            'id' => 'country',
            'class' => 'form-control select2',
            'placeholder' => 'Select Country',
            'required',
        ]) !!}
    </div>
    @php
        if (isset($model)) {
            $headerImage = $model->pad_header_image != '' ? $model->pad_header_image_path : '';
            $footerImage = $model->pad_footer_image != '' ? $model->pad_footer_image_path : '';
            $logo = $model->logo != '' ? $model->company_logo_path : '';
        } else {
            $headerImage = '';
            $footerImage = '';
            $logo = '';
        }

    @endphp


    <div class="form-group">
        <label for="logo">Logo (Image must be JPG) </label>
        {{-- {!! Form::file('logo',null,['id'=>'logo','class'=>'form-control','placeholder'=>'Enter Logo']); !!} --}}
        <input type="file" id='logo' class="form-control" accept="image/*" name="logo"
            placeholder="Import image" onchange="getImagePreview(this)">
        <input type="hidden" id="front-image-url" value="">
        <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
        </div>

        <br />
        @if ($logo)
            <img src="{{ $logo }}" style="max-height: 70px; width: 70px !important;" alt=""> <br />
        @endif
    </div>
    {{-- <div class="form-group"> --}}
    {{-- <label for="logo">Report Head Image  (Image must be JPG) </label> --}}
    {{-- {!! Form::file('report_head_image',null,['id'=>'report_head_image','class'=>'form-control']); !!}  <br/> --}}
    {{-- </div> --}}
    <div class="form-group">
        <label for="city">Pad Top Header Size (Height px) </label>
        {!! Form::number('pad_header_height', null, [
            'class' => 'form-control',
            'placeholder' => 'Enter pad header height',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="logo">Pad Top Header Image (Image must be JPG) </label>
        {{-- {!! Form::file('pad_header_image', null, ['class' => 'form-control']) !!} --}}

        <input type="file" id='' class="form-control" accept="image/*" name="pad_header_image"
            placeholder="Import image" onchange="getImagePreview(this)">
        <input type="hidden" id="front-image-url" value="">
        <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
        </div>
        <br />
        @if ($headerImage)
            <img src="{{ $headerImage }}" width="100%" style="max-height: {{ $model->pad_header_height }}px;"
                alt="">
        @endif

    </div>
    <div class="form-group">
        <label for="city">Pad Bottom Footer Size (Height px) </label>
        {!! Form::number('pad_footer_height', null, [
            'class' => 'form-control',
            'placeholder' => 'Enter pad footer height',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="city">Show Room Number (Outdoor Patient Registration) </label>
        {!! Form::select('show_room_status',['0'=>'No','1'=>'Yes'], null,['id'=>'show_room_status','class'=>'form-control']); !!}

    </div>
    <div class="form-group">
        <label for="logo">Pad Bottom Footer Image (Image must be JPG) </label>
        {{-- {!! Form::file('pad_footer_image', null, ['class' => 'form-control']) !!}  --}}
        <input type="file" id='' class="form-control" accept="image/*" name="pad_footer_image"
            placeholder="Import image" onchange="getImagePreview(this)">
        <input type="hidden" id="front-image-url" value="">
        <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
        </div>
        <br />
        @if ($footerImage)
            <img src="{{ $footerImage }}" width="100%" style="max-height: {{ $model->pad_header_height }}px;"
                alt="">
        @endif

    </div>
    <div class="form-group">
        <label for="phone">Set Weekend </label>
        <select name="weekend[]" class="form-control select2" multiple>
            @foreach (get_daysOfWeek() as $value)
                <option value="{{ $value }}"
                    {{ isset($model) ? (in_array($value, $model->weekend->pluck('name')->toArray()) ? 'selected' : '') : '' }}>
                    {{ $value }}</option>
            @endforeach
        </select>

    </div>
</div>

@push('scripts')
    <!-- Date range picker -->

    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>

    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <script src="{{ asset('public/adminLTE/dist/js/jasny-bootstrap.min.js') }}"></script>




    <script type="text/javascript">
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
