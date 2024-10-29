<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/26/2019
 * Time: 10:49 AM
 */
?>

<!-- Modal -->
<div id="addCustomer" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{__('common.add_new_customer')}}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <label for="name">{{__('common.customer_name')}}:  <span class="text-red"> * </span> </label>
                    {!! Form::text('name', null,['id'=>'name','class'=>'form-control','placeholder'=>trans('common.enter_name'), 'required']); !!}
                </div>
                <div class="form-group">
                    <label for="phone">{{__('common.phone_number')}}: <span class="text-red"> * </span> </label><br/>
                    {!! Form::text('phone', null,['id'=>'phone','class'=>'form-control','placeholder'=>trans('common.enter_phone_number'), 'required']); !!}
                </div>
                <div class="form-group">
                    <label for="address">{{__('common.address')}}: <span class="text-red"> * </span> </label><br/>
                    {!! Form::text('address', null,['id'=>'address','class'=>'form-control','placeholder'=>trans('common.enter_address'), 'required']); !!}
                </div>
                <div class="form-group">
                    <label for="email">{{__('common.email')}}:  </label><br/>
                    {!! Form::text('email', null,['id'=>'phone','class'=>'form-control','placeholder'=>trans('common.enter_email')]); !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('common.close')}}</button>
                <button type="button" class="btn btn-success" id="save-customer"> {{__('common.save')}} </button>
            </div>
        </div>

    </div>
</div>


@push('scripts')
<script type="text/javascript">


    $(function () {
        $('#save-customer').click(function (e) {
        var url = "{{ route('member.customer.save') }}";
        var form_data = {
            '_token': '{{ csrf_token() }}',
            'name' : $('#name').val(),
            'phone' : $('#phone').val(),
            'address' : $('#address').val(),
            'email' : $('#email').val()
        };

        $.ajax({
            type        : 'POST',
            url         : url, // the url where we want to POST
            data        : form_data,
            dataType    : 'json',
            encode      : true,
        }).done(function(data) {

            if(data.status==1 && data.values.id != undefined){
                // console.log(data.values.id);
                $("#customer_id").append('<option value="'+data.values.id+'" selected>'+data.values.name+'('+data.values.phone+')</option>');
                $("#customer_id").trigger('change.select2');

                $('#name').val('');
                $('#phone').val('');
                $('#email').val('');
                $('#address').val('');
                $('#addCustomer').modal('toggle');
            }else{
                bootbox.alert("Name/phone is required & Unique and Address Required");
            }
            // console.log(data);

        }).fail(function (jqXHR, textStatus, errorThrown) {
            var error = $.parseJSON(jqXHR.responseText);
            // console.log(error);
            bootbox.alert("Name/phone is required & Unique and Address Required");
            // alert(textStatus);
            // alert(errorThrown);
        });

        e.preventDefault();
        });
    });
</script>

@endpush
