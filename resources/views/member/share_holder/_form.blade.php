<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>

@push('styles')

    {{-- <link rel="stylesheet" type="text/css" href="dist/bootstrap-clockpicker.min.css">
     --}}
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.css" integrity="sha512-BB0bszal4NXOgRP9MYCyVA0NNK2k1Rhr+8klY17rj4OhwTmqdPUQibKUDeHesYtXl7Ma2+tqC6c7FzYuHhw94g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush


<div class="col-md-6">


    <div class="form-group ">
        <label for="name">{{__('Name')}} <span class="text-red"> * </span> </label>

        {!! Form::text('name',null,['id'=>'name','class'=>'form-control input-group', 'data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('name'), 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Father Name')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('father_name',null,['id'=>'father_name','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Father Name'), 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Mother Name')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('mother_name',null,['id'=>'mother_name','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Mother Name'), 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Nominee Name')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('nominee',null,['id'=>'nominee','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Nominee Name'), 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Phone')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('phone',null,['id'=>'phone','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Phone'), 'required']); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Email')}}   </label>
        {!! Form::text('email',null,['id'=>'email','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Email')]); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Address')}}   </label>
        {!! Form::text('address',null,['id'=>'address','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Address')]); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Passport Number')}}   </label>
        {!! Form::text('passport_number',null,['passport_number'=>'email','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Passport Number')]); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('NID Number')}}   </label>
        {!! Form::text('nid_number',null,['id'=>'nid_number','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('NID Number')]); !!}
    </div>

    <div class="form-group">
        <label for="name">{{__('Number of Share')}}   </label>
        {!! Form::text('share_number',null,['id'=>'share_number','class'=>'form-control input-group','data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>trans('Number of Share')]); !!}
    </div>

    <div class="form-group">
        <label for="name"> {{__('Is Management')}}  </label>
        {!! Form::select('type',['0'=>'No','1'=>'Yes'], null,['id'=>'type','class'=>'form-control', 'required']); !!}
        {{--{!! Form::select('shift_type',['Morning'=>'Morning', 'Day'=>'Day','Night'=>'Night'], null,['id'=>'shift_type','class'=>'form-control', 'required']); !!}--}}
    </div>

    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
        <label for="lead_category_id">Image </label>
        <div>
            @if (@$model && @$model->image)
            <input type="file" data-default-file="{{asset('/public/uploads/share_holder/'.$model->image)}}"
            name="image" class="dropify" data-max-file-size="1M" data-height="200"
            data-allowed-file-extensions="jpg jpeg png"  accept="image/png, image/jpg, image/jpeg" />
            @else
            <input type="file"
            name="image" class="dropify" data-max-file-size="1M" data-height="200" data-allowed-file-extensions="jpg jpeg png"  accept="image/png, image/jpg, image/jpeg" />
            @endif

        </div>
    </div>

    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
        <label for="lead_category_id">Signature Image</label>
        <div>
            @if (@$model && @$model->signature_image)
            <input type="file" data-default-file="{{asset('/public/uploads/share_signature_image/'.$model->signature_image)}}"
            name="image" class="dropify" data-max-file-size="1M" data-height="200"
            data-allowed-file-extensions="jpg jpeg png"  accept="image/png, image/jpg, image/jpeg" />
            @else
            <input type="file"
            name="share_signature_image" class="dropify" data-max-file-size="1M" data-height="200" data-allowed-file-extensions="jpg jpeg png"  accept="image/png, image/jpg, image/jpeg" />
            @endif

        </div>
    </div>

</div>
@push('scripts')
    <!-- Select2 -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.js" integrity="sha512-1QoWYDbO//G0JPa2VnQ3WrXtcgOGGCtdpt5y9riMW4NCCRBKQ4bs/XSKJAUSLIIcHmvUdKCXmQGxh37CQ8rtZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
     $('.clockpicker').clockpicker({
        twelvehour: true,
        donetext: "Done",
        autoclose: false,
     })
     .find('input').change(function(){

    });
    </script>

@endpush

