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

@include('common._error')
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Title <span class="text-red"> * </span> </label>
            {!! Form::text('title',old('title') ,['id'=>'name','class'=>'form-control','placeholder'=>'Enter title', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="phone">Address  <span class="text-red"> * </span> </label>
            {!! Form::text('address',old('address'),['id'=>'address','class'=>'form-control','placeholder'=>'Enter address', 'required' ]); !!}
        </div>
        {{--<div class="form-group">--}}
            {{--<label for="address" >Contact Person NID   <span class="text-red"> * </span>  </label>--}}
            {{--{!! Form::text('nid',old('nid'),['id'=>'contact_person','class'=>'form-control','placeholder'=>'Enter NID', 'required']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="address" >Latitude  </label>--}}
            {{--{!! Form::text('latitude',old('latitude'),['id'=>'latitude','class'=>'form-control','placeholder'=>'Enter latitude']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="region_id">Region </label>--}}
            {{--{!! Form::select('region_id', $regions, null,['id'=>'region_id','class'=>'form-control select2','placeholder'=>'Select Region']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="district_id">District </label>--}}
            {{--{!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>'Select District']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="area_id">Area </label>--}}
            {{--{!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Select Area']); !!}--}}
        {{--</div>--}}
        <div class="form-group">
            <label for="status">Status <span class="text-red"> * </span>  </label>
            {!! Form::select('active_status',['1'=>'Active', '0'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required' ]); !!}
        </div>
        {{--<div class="form-group">--}}
            {{--<label for=file> Featured Image  </label>--}}
            {{--<input  name="featured_image" type="file" accept="image/*" />--}}
        {{--</div>--}}

        {{--@if(isset($model) && $model->featured_image != '')--}}
            {{--<div class="form-group">--}}
                {{--<img src="{{ $model->featured_image_path }}" style="width: 200px !important;"/>--}}
            {{--</div>--}}
        {{--@endif--}}

        {{--<div class="form-group">--}}
            {{--<label for=file> Gallery Photo  </label>--}}
            {{--<input  name="gallery_image[]" type="file" accept="image/*" multiple/>--}}
        {{--</div>--}}

        {{--@if(isset($model) && $model->gallery_images != '')--}}
            {{--<div class="form-group">--}}
                {{--@foreach($model->gallery_images_path as $value)--}}
                {{--<img src="{{ $value }}" style="width: 200px !important;"/>--}}
                    {{--@endforeach--}}
            {{--</div>--}}
        {{--@endif--}}

    </div>

<div class="col-md-6">
        <div class="form-group">
            <label for="email"> Mobile  <span class="text-red"> * </span> </label>
            {!! Form::text('mobile',old('mobile'),['id'=>'mobile','class'=>'form-control input-number','placeholder'=>'Enter mobile', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="address" >Contact Person   <span class="text-red"> * </span>  </label>
            {!! Form::text('contact_person',old('contact_person'),['id'=>'contact_person','class'=>'form-control','placeholder'=>'Enter contact person', 'required']); !!}
        </div>
        {{--<div class="form-group">--}}
            {{--<label for="address" >Longitude  </label>--}}
            {{--{!! Form::text('longitude',old('longitude'),['id'=>'longitude','class'=>'form-control','placeholder'=>'Enter longitude']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="division_id">Division </label>--}}
            {{--{!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>'Select Division']); !!}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label for="thana_id">Thana </label>--}}
            {{--{!! Form::select('thana_id', $thanas, null,['id'=>'thana_id','class'=>'form-control select2','placeholder'=>'Select Thana']); !!}--}}
        {{--</div>--}}

</div>



@push('scripts')

    <!-- Date range picker -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        // var date = new Date();
        $(function () {
            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.select2').select2();

            $("#division_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('search.select_district') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    console.log(data);
                    if(data.status == "success")
                    {
                        var html = "<option value=''>Select City </option>";

                        $.each( data.modals, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });

                        $('#district_id').html(html);
                    }else{
//                        console.log(data);
                        bootbox.alert("No data Found!! ");
                    }
                });
            });
            $("#district_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('search.select_upazilla') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    if(data.status == "success")
                    {
                        var html = "<option value=''>Select upazilla </option>";

                        $.each( data.modals, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });

                        $('#upazilla_id').html(html);
                    }else{
//                        console.log(data);
                        bootbox.alert("No data Found!! ");
                    }
                });
            });
            $("#upazilla_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('search.select_union') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    if(data.status == "success")
                    {
                        var html = "<option value=''>Select Union </option>";

                        $.each( data.unions, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });
                        $('#union_id').html(html);
                        var html = "<option value=''>Select Area </option>";

                        $.each( data.areas, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });

                        $('#area_id').html(html);
                    }else{
                        bootbox.alert("No data Found!! ");
                    }
                });
            });
        });
    </script>
@endpush
