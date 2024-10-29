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

<div class="col-md-7">
    <div class="form-group">
        <label for="full_name">Full Name <span class="text-red"> * </span> </label>
        {!! Form::text('full_name',null,['id'=>'full_name','class'=>'form-control','placeholder'=>'Enter Full Name', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="email">Email <span class="text-red"> * </span> </label>
        {!! Form::email('email',null,['id'=>'email','class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Enter Email', 'required']); !!}
    </div>
    @if(!isset($model))
        <div class="form-group">
            <label for="password">Password <span class="text-red"> * </span> </label>
            <input type="password" class="form-control" onfocus="this.value=''" value="" autocomplete="new-password"
                   name="password" required placeholder="Password">
        </div>
    @else
        {{-- <div class="form-group">
            <label for="password">Change Password </label>
            <input type="password" class="form-control "  onfocus="this.value=''" value="" autocomplete="new-password" name="new_password" placeholder="Password">
        </div> --}}
    @endif
    <div class="form-group">
        <label for="phone">Phone </label>
        {!! Form::text('phone',null,['id'=>'phone','class'=>'form-control input-number','placeholder'=>'Enter phone']); !!}
    </div>

    {{--@if(Auth::user()->hasRole(['super-admin', 'developer']) && $form_type == 'edit')--}}
    {{--<div class="form-group">--}}
    {{--<label for="company_id">Packages </label>--}}
    {{--{!! Form::select('membership_id',$packages,null,['id'=>'membership_id','class'=>'form-control select2','placeholder'=>'Select Package']); !!}--}}
    {{--@endif--}}


    @if( config('settings.requisition'))
        <div class="form-group">
            <label for="company_id">Customer/Supplier Name </label>
            {!! Form::select('customer_id',$sharers, isset($model) && $model->sharer ? $model->sharer->id : null,['id'=>'customer_id','class'=>'form-control select2','placeholder'=>'Select Customer/Supplier']); !!}
        </div>
    @endif

    {{--@if(Auth::user()->can(['super-admin']) && $form_type == 'edit')--}}
    {{--<div class="form-group">--}}
    {{--<label for="company_id">Company </label>--}}
    {{--{!! Form::select('company_id',$companies,null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}--}}
    {{--</div>--}}
    {{--@elseif(Auth::user()->can(['super-admin']) && $form_type == 'create')--}}
    {{--<div class="form-group">--}}
    {{--<label for="name">Company Name<span class="text-red"> * </span> </label>--}}
    {{--{!! Form::text('company_name',null,['id'=>'company_name','class'=>'form-control','placeholder'=>'Enter Company Name', 'required']); !!}--}}
    {{--</div>--}}
    {{--<div class="form-group">--}}
    {{--<label for="display_name">Company Address <span class="text-red"> * </span> </label>--}}
    {{--{!! Form::text('address',null,['id'=>'address','class'=>'form-control','placeholder'=>'Enter Address',  'rows'=>"2"]); !!}--}}
    {{--</div>--}}
    {{--@endif--}}


</div>
<div class="col-md-12 mb-5">
    <div class="form-group ">
        <label for="role">{{ isset($assignRole)?'Assigned ':'' }} Roles </label><br/>

        @foreach( $roles as $key => $role)
            <div class="col-md-3">
                @if(isset($assignRole))
                    {!! Form::checkbox('roles[]', $key, null, [ 'checked' => in_array($key, $assignRole )] ) !!} {{ $role }}
                @else
                    {!! Form::checkbox('roles[]', $key, null ) !!} {{ $role }}
                @endif
            </div>
        @endforeach
    </div>
    <br/>
</div>

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('input').attr('autocomplete', 'off');

            $('.select2').select2();

        });
    </script>
@endpush

