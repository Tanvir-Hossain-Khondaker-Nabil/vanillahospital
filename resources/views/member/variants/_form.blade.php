<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:55 PM
 */
?>


@push('styles')

<link rel="stylesheet"  href="{{ asset('public/adminLTE/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush


<div class="col-md-6">

    <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }} ">
        <label for="name">{{__('common.variant_name')}} <span class="text-red"> * </span> </label>
        {!! Form::text('title', null, [
            'id' => 'title',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_variant_name'),
            'required',
        ]) !!}
    </div>

{{--    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">--}}
{{--        <label for="status">Type </label>--}}
{{--        {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, [--}}
{{--            'id' => 'status',--}}
{{--            'class' => 'form-control',--}}
{{--        ]) !!}--}}
{{--    </div>--}}

</div>


<div class="col-md-6">
    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">{{__('common.select_status')}} </label>
        {!! Form::select('status', ['active' => trans('common.active'), 'inactive' => trans('common.inactive')], null, [
            'id' => 'status',
            'class' => 'form-control',
        ]) !!}
    </div>
</div>

<div class="col-md-12">
    <table class="table table-responsive mb-2">
        <thead>
            <tr>
                <th> {{__('common.variants')}}</th>
{{--                <th> Image/Color Type</th>--}}
{{--                <th> Input Value</th>--}}
{{--                <th> Preview </th>--}}
                <th>  </th>
            </tr>
        </thead>
        <tbody id="variant_row">

        @if(isset($modal))
            @foreach($modal->variant_values as $key => $value)
            <tr>
                <td style="width: 250px;">
                    {!! Form::hidden('name_id[]', $value->id) !!}
                    {!! Form::text('name[]', $value->name, [
                        'id' => 'name',
                        'class' => 'form-control',
                        'placeholder' => trans('common.enter_name'),
                        'required',
                    ]) !!}
                </td>
                {{--            <td style="width: 200px;">--}}
                {{--                {!! Form::select('types[]', ["image"=>"Image", 'color'=>"Color"], null,['data-id'=>'0', 'class'=>'form-control types']); !!}--}}
                {{--            </td>--}}
                {{--            <td style="width: 250px;">--}}
                {{--                <input type="file" id="image_0" class=" form-control" accept="image/*" name="image[]" placeholder="Image">--}}
                {{--                <input type="text" id="color_0" class="hidden form-control" name="color[]" >--}}
                {{--            </td>--}}
                {{--            <td id="">--}}
                {{--            </td>--}}
                <td>
                    @if($key>0)
                        <a href="javascript:void(0)" class="delete-row btn btn-danger btn-sm"> <i class="fa fa-times-circle "></i> </a>
                    @endif
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td style="width: 250px;">
                    {!! Form::text('name[]', null, [
                        'id' => 'name',
                        'class' => 'form-control',
                        'placeholder' => trans('common.enter_name'),
                        'required',
                    ]) !!}
                </td>
                {{--            <td style="width: 200px;">--}}
                {{--                {!! Form::select('types[]', ["image"=>"Image", 'color'=>"Color"], null,['data-id'=>'0', 'class'=>'form-control types']); !!}--}}
                {{--            </td>--}}
                {{--            <td style="width: 250px;">--}}
                {{--                <input type="file" id="image_0" class=" form-control" accept="image/*" name="image[]" placeholder="Image">--}}
                {{--                <input type="text" id="color_0" class="hidden form-control" name="color[]" >--}}
                {{--            </td>--}}
                {{--            <td id="">--}}
                {{--            </td>--}}
                <td>

                </td>
            </tr>

        @endif

        </tbody>
    </table>
</div>

<div class="col-md-12 mb-5">
    <a class="btn btn-primary add-row" href="javascript:void(0)" ><i class="fa fa-plus-circle"></i> {{__('common.add_row')}}</a>
</div>

@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();
        $(function() {

            // $('#color_code').colorpicker();
            $('.select2').select2();


            $(document).on('click','.add-row', function() {

                var rowHtml = `<tr>
                    <td style="width: 250px;">
                        <input id="name" class="form-control" placeholder="{{__('common.enter_name')}}" required="" name="name[]" type="text">
                    </td>
<!--                    <td style="width: 200px;">-->
<!--                        <select class="form-control types" data-id="1" name="types[]">-->
<!--                            <option value="image">{{__('common.image')}}</option>-->
<!--                            <option value="color">{{__('common.color')}}</option>-->
<!--                        </select>-->
<!--                    </td>-->
<!--                    <td style="width: 250px;">-->
<!--                        <input type="file" id="image_1" class="d-none form-control" accept="image/*" name="image[]" placeholder="{{__('common.image')}}">-->
<!--                        <input type="text" id="color_1" class="d-none form-control" name="color[]" >-->
<!--                    </td>-->
<!--                    <td id="">-->

<!--                    </td>-->
                    <td> <a href="javascript:void(0)" class="delete-row btn btn-danger btn-sm"> <i class="fa fa-times-circle "></i> </a> </td>
                </tr>`;

                $("#variant_row").append(rowHtml);

            });


            $(document).on('click', '.delete-row', function () {

                var $this = $(this);

                bootbox.confirm("{{__('common.are_you_sure_to_delete_this_row')}}",
                    function(result) {
                        if(result==true)
                        {
                            $this.parent().parent().remove();
                        }
                    });

            });

        });
    </script>
@endpush
