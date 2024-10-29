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
            <label for="name">Full Name <span class="text-red"> * </span> </label>
            {!! Form::text('name',old('name') ,['id'=>'name','class'=>'form-control','placeholder'=>'Enter Full Name', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="email">Email </label>
            {!! Form::email('email',null,['id'=>'email','class'=>'form-control','placeholder'=>'Enter Email']); !!}
        </div>
        <div class="form-group">
            <label for="division_id">State </label>
            {!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>'Select State']); !!}
        </div>
        {{-- <div class="form-group">
            <label for="upazilla_id">Upazilla </label>
            {!! Form::select('upazilla_id', $upazillas, null,['id'=>'upazilla_id','class'=>'form-control select2','placeholder'=>'Select Upazilla']); !!}
        </div> --}}
        <div class="form-group">
            <label for="area_id">Area </label>
            {!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Select Area']); !!}
        </div>
</div>

<div class="col-md-6">
        <div class="form-group">
            <label for="phone">Phone  <span class="text-red"> * </span> </label>
            {!! Form::text('phone',null,['id'=>'phone','class'=>'form-control input-number','placeholder'=>'Enter phone', 'required' ]); !!}
        </div>
        <div class="form-group">
            <label for="address" >Address   </label>
            {!! Form::text('address',null,['id'=>'address','class'=>'form-control','placeholder'=>'Enter address']); !!}
        </div>
    <div class="form-group">
        <label for="district_id">City </label>
        {!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>'Select City']); !!}
    </div>
    {{-- <div class="form-group">
        <label for="union_id">Union </label>
        {!! Form::select('union_id', $unions, null,['id'=>'union_id','class'=>'form-control select2','placeholder'=>'Select Union']); !!}
    </div> --}}
    <div class="form-group">
        <label for="status">Status <span class="text-red"> * </span>  </label>
        {!! Form::select('status',['active'=>'Active', 'inactive'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required' ]); !!}
    </div>

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
