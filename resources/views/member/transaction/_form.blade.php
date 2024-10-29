<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:55 PM
 */
?>


@push('styles')

    <style type="text/css">
        .content{
            font-size: 11px;
        }
        textarea.form-control{
            height: 108px;
        }
        .head-trans div{
            padding-left: 5px !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush


<div class="box-group head-trans">
    <input type="hidden" name="transaction_method" value="{{ $type }}"/>
    <div class="col-md-3">
        <label for="cash_or_bank_id">  {{__("common.".strtolower($type))}}  {{__('common.from')}} </label>
        <select required name="cash_or_bank_id" id='cash_or_bank_id' class='form-control select2' placeholder='Select Account'>
            <option value="">{{__('common.please_select')}}</option>
            @foreach($accounts as $value)
                <option value="{{ $value->id }}" data-action="{{ $value->account_type_id }}" data-option="{{ $value->current_balance }}">{{ $value->title }}</option>
            @endforeach
        </select>
        <div class="form-group" id="current-balance">
        </div>
    </div>
    <div class="col-md-3">
        <label for="date"> {{__('common.date')}} </label>
        {!! Form::text('date',null,['id'=>'date','class'=>'form-control','autocomplete'=>'off','placeholder'=>trans('common.enter_date'), 'required']); !!}
    </div>
    <div class="col-md-3">
        <label for="payer"> {{ $type=='Payment' ? __('common.received_person') : __('common.sender') }} {{__('common.type')}} </label>
        {!! Form::select('payer_type', ['customer'=>'Customer','supplier'=>'Supplier','both'=>'Customer & Supplier'], null,['id'=>'payer','class'=>'form-control ','placeholder'=>trans('common.please_select')]); !!}
    </div>

    <div class="col-md-3">
        <label for="supplier_id"> {{ $type=='Payment' ?  __('common.received_by'): __('common.sender_by') }}  </label>
        {!! Form::select('supplier_id', $sharers , null,['id'=>'supplier_id','class'=>'form-control select2','placeholder'=>trans('common.please_select')]); !!}
    </div>
</div>



<div class="box main-box">
    <div class="box-body">
        <div class="col-md-1">
            <label for="category_id">{{ $type == "Expense" ? __('common.expense') : "" }} {{__('common.code')}} </label>
            {!! Form::text('account_type[]', null,['id'=>'account_id_0', 'data-option'=>'0','class'=>'form-control acc_id', 'placeholder'=>trans('common.select_account_code')]); !!}
        </div>
        @php

            // $str = ;
        @endphp
        <div class="col-md-3">
            <label for="category_id">{{ $type == "Expense" ? __('common.account_to') : __("common.".strtolower($type))." ".__("common.to") }} </label>
            {!! Form::select('account_type_id[]', $transaction_categories, null,['id'=>'account_name_0', 'data-option'=>'0', 'class'=>'form-control acc_name select2 acc_name','required', 'placeholder'=>trans('common.please_select')]); !!}
            {{--            <button class="btn btn-primary btn-at"  data-toggle="modal" data-target="#myModal" data-option="0" type="button"><i class="fa fa-plus-square-o"></i> Add Account Name</button>--}}
        </div>
        <div class="col-md-2" >
            <label for="category_id">{{__('common.payment_method')}}</label>
            {!! Form::select('payment_method[]', $payment_methods, null,['id'=>'payment_method_0', 'data-option'=>'0', 'class'=>'form-control select2 payment_method_change','required']); !!}
            <div class="payment_method_addition">
            </div>
        </div>
        <div class="col-md-1">
            <label for="amount"> {{__('common.amount')}} </label>
            {!! Form::text('amount[]',null,['id'=>'amount','class'=>'form-control input-number','placeholder'=>trans('common.enter_amount'), 'step'=>"0.000", 'required']); !!}
        </div>
        <div class="col-md-3">
            <label for="description">{{__('common.description')}}   </label>
            {!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>trans('common.enter_description'),'height'=>'70px !important' ]); !!}
        </div>
        <div class="col-md-1">
            <a class="btn btn-success add-row pull-right margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a>
        </div>
    </div>
</div>
<div class="box">
    <div class="box-body">
        <div class="col-md-12">
            <div class="form-group">
                <label for="notation">{{__('common.notation')}}   </label>
                {!! Form::textarea('notation',null,['id'=>'notation','class'=>'form-control' ,'placeholder'=>trans('common.enter_notation'),'height'=>'70px !important' ]); !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for=file>{{__('common.attach_if_file_available')}}  </label>
                {{-- <input class="form-control" name="attach" type="file"/> --}}

            <input type="file" id='attach' class="form-control" accept="image/*" name="attach"
                placeholder="Import image" onchange="getImagePreview(this)">
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
            </div>
            </div>
        </div>
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
            var today = moment().format('MM\DD\YYYY');
            $('#date').datepicker('setDate', today);
            $('#date').datepicker('update');

            $('.select2').select2();

            var transaction_categories_id = [];
            transaction_categories_id = <?php print_r(json_encode($transaction_categories_id)); ?>;
            var transaction_categories  = <?php print_r(json_encode($transaction_categories)); ?>;
            var catgories = transaction_categories;

            var optionName;
            function transaction_category() {

                optionName='<option selected="selected" value="">Select Account Name</option>';

                $.each( catgories, function( key, value ) {
                    optionName = optionName+'<option value="'+key+'" >'+value+'</option>';
                });

                return optionName;
            }

            var optionPM = '';
            @foreach($payment_methods as $key=>$value)
                optionPM = optionPM+'<option value="<?=$key?>" <?=$key==1 ? 'selected="selected"' : ''?> ><?=$value?></option>';
                @endforeach



            var i = 1;

            $(document).on('click','.add-row', function() {

                optionName = transaction_category();
                var $div = $(this).parent().parent('.box-body');
                var childrenDiv  = $div.children();

                if( childrenDiv.find('select').val() == ""){
                    alert('Account Name can\'t be Empty');
                    return false;
                }


                if(childrenDiv.find('input#amount').val() == ""){
                    alert('Amount can\'t be Empty');
                    return false;
                }


                var payment_method = '<div class="col-md-2"><label for="payment_method_'+i+'">Payment Method </label>'+
                    '<select id="payment_method_'+i+'" data-option="'+i+'" class="form-control select2 payment_method_change" required="" name="payment_method[]" >'
                    +optionPM+'</select><div class="payment_method_addition form-group" >' +
                    '</div></div>';


                var html = '<div class="box-body">'+
                    '<div class="col-md-2">'+
                    '<label for="category_id">Account Code </label>'+
                    '<input placeholder="Select Account Code" id="account_id_'+i+'" data-option="'+i+'" class="form-control acc_id" name="account_type[]"/>'+
                    '</div> <div class="col-md-3">'+
                    '<label for="category_id">Account Name </label>'+
                    '<select id="account_name_'+i+'" data-option="'+i+'" class="form-control acc_name select2 acc_name" name="account_type_id[]" required>'+optionName+
                    '</select>'+
                    // '<button class="btn btn-primary btn-at" data-toggle="modal" data-target="#myModal"  data-option="'+i+'" type="button"><i class="fa fa-plus-square-o"></i> Add Account Name</button>'+
                    '</div>'+payment_method+
                    '<div class="col-md-3">'+
                    '<label for="amount"> Amount </label>'+
                    '{!! Form::text('amount[]',null,['id'=>'amount','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"0.000", 'required']); !!}'+
                    '</div>'+
                    '<div class="col-md-3">'+
                    '<label for="description">Description  </label>'+
                    '{!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}'+
                    '</div>'+
                    '<div class="col-md-1"> <a class="btn btn-success add-row pull-right margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a>'+
                    ' </div>'+
                    '</div>';

                i++;

                $(this).parent().css("padding-top", '33px');
                // console.log(childrenDiv);
                childrenDiv.find('input').attr('readonly','readonly');
                $('#cash_or_bank_id').attr('readonly', true);
                childrenDiv.find('select').attr('readonly', true);
                var actionHtml = '<a href="#" class="btn-text-info margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a></span>';
                $(this).parent('div').html(actionHtml);
                // alert(html);
                // $( ".box" ).lastChild('.box-body').append( html );
                $( ".main-box" ).last().append( html );
                $('.select2').select2();

            });

            $(document).on('click','.edit-button', function() {
                var $mainDiv = $(this).parent().parent().parent('.main-box').children();
                $mainDiv.find('input').attr('readonly', true);
                $mainDiv.find('select').attr('readonly', true);

                var $div = $(this).parent().parent('.box-body');
                var childrenDiv = $div.children();
                $(this).parent().css("padding-top", '0px');
                childrenDiv.find('input').attr('readonly', false);
                childrenDiv.find('select').attr('readonly', false);
                var actionHtml = '<a class="btn btn-warning pull-right margin-top-23" id="edit-complete" href="#" ><i class="fa fa-check-circle"></i> </a>';
                $(this).parent('div').html(actionHtml);
            });

            $(document).on('click','.delete-field', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent('.box-body');
                $div.remove();
            });

            $(document).on('click','#edit-complete', function() {

                var $div = $(this).parent().parent('.box-body');
                var childrenDiv = $div.children();
                $(this).parent().css("padding-top", '33px');
                childrenDiv.find('input').attr('readonly', true);
                childrenDiv.find('select').attr('readonly', true);
                var actionHtml = '<a href="#" class=" btn-text-info margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a>';
                $(this).parent('div').html(actionHtml);

                var $lastDiv = $('.main-box').children('.box-body').last();
                console.log($lastDiv);

                // console.log($mainDiv);
                $lastDiv.find('input').attr('readonly', false);
                $lastDiv.find('select').attr('readonly', false);
            });

            var target = '';

            $(document).on('click','.btn-at', function() {
                var $el = $(this);
                target = $el.data('option');
            });

            $(document).on('keyup','.acc_id', function(){

                var acc_value = $(this).val();
                var row = $(this).data('option');

                // var a = transaction_categories_id.in_array(value);

                $.each(transaction_categories_id, function (i, value) {

                    // console.log(i+value);
                    if(acc_value.indexOf(value) != -1)
                    {
                        $('#account_name_'+row).val(i);
                        $('#account_name_'+row).trigger('change.select2');
                    }
                });


                // $('#account_id_'+row).val(value);
                // $('#account_id_'+row).trigger('change.select2');
                $('#account_name_'+row).val(acc_value);
                $('#account_name_'+row).trigger('change.select2');
            });


            $(document).on('change','.acc_name', function(){

                var acc_value = $(this).val();
                var row = $(this).data('option');
                $('#account_id_'+row).val(acc_value);
            });


            $("#cash_or_bank_id").change( function(e){
                e.preventDefault();
                var value =  $(this).find(':selected').data('option');
                var action =  $(this).find(':selected').data('action');
                catgories = transaction_categories;
                delete catgories[action];
                var html = transaction_category();
                $('#account_name_0').html(html);
                $('#account_name_0').select();
                var balance  = parseFloat(value);
                var color = balance < 0 ? "red" : "blue";
                var html = "<b class='text-"+color+"'> Current Balance: "+balance+"</b>";
                $('#current-balance').html(html);
            });

            // $(document).on('change','.payment_method_change', function(e){
            //     e.preventDefault();
            //     var input = $(this).parent().children('input');
            //     var selectedValue = $(this).find("option:selected").val();
            //     input.val(selectedValue);
            //
            //     var value = $(this).find("option:selected").text().toLowerCase();
            //     var option = $(this).data('option');
            //     var html = '<input class="form-control" name="issuer_name_'+option+'" value="" placeholder="Issuer Name" />';
            //
            //     if(value=='check')
            //     {
            //         html = html+'<input class="form-control" name="check_number_'+option+'" value="" placeholder="Check Number" />'
            //                 +'<input class="form-control" name="receiver_name_'+option+'" value="" placeholder="Receiver Name" />'
            //                 +'<input class="form-control date issue_date" name="issue_date_'+option+'" value="" placeholder="Issue Date" />'
            //                 +'<input class="form-control date" name="pass_date_'+option+'" value="" placeholder="Pass Date" />'
            //                 +'<input class="form-control date" name="provide_date_'+option+'" value="" placeholder="Provide Date" />';
            //
            //     }else if(value=='paypal')
            //     {
            //         html = html+'<input class="form-control" name="payby_email_'+option+'" value="" placeholder="PayPal Email" type="email" />'
            //     }else if(value=='bkash'){
            //         html = html+'<input class="form-control" name="mobile_number_'+option+'" value="" placeholder="Bkash Number" />'
            //     }else if(value=='credit card'){
            //         html = html+'<input class="form-control" name="card_number_'+option+'" value="" placeholder="Credit Card Number"  />'
            //             +'<input class="form-control date" name="expire_date_'+option+'" value="" placeholder="Expire Date" />';
            //     }else if(value=='master card'){
            //         html = html+'<input class="form-control" name="card_number_'+option+'" value="" placeholder="Master Card Number"  />'
            //             +'<input class="form-control date" name="expire_date_'+option+'" value="" placeholder="Expire Date" />';
            //
            //     }
            //
            //     $(this).parent().children('.payment_method_addition').html(html);
            //
            //     $('.date').datepicker();
            //     $('.issue_date').datepicker({
            //         "format": 'mm/dd/yyyy',
            //         "endDate": "+0d",
            //         "todayHighlight": true,
            //         "autoclose": true
            //     });
            // });


            $('#payer').change( function (e) {
                var value = $(this).val();
                var url = "{{ route('member.payer.search') }}";
                var form_data = {
                    'customer_type' : value
                };
                var type = " <?= $type=='Payment' ? 'Given By': 'Receiver By'; ?>";
                var option='<option value="">Select'+type+'</option>';
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    if(data.status==1){
                        $.each( data.values, function( index, value ) {
                            option = option+"<option value='"+index+"'>"+ value+"</option>";
                        });
                        $('#supplier_id').empty().append(option);
                        $('#supplier_id').select2();
                    }else{
                        alert("Data Not Found");
                    }
                });

                e.preventDefault();
            });

            $('#save-account').click(function (e) {
                var url = "{{ route('member.account_type.save') }}";
                var form_data = {
                    '_token': '{{ csrf_token() }}',
                    'display_name' : $('#account_type_name').val(),
                    'parent_id' : $('#parent_id').val()
                };

                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true,
                }).done(function(data) {

                    if(data.status==1){
                        console.log(data.values.id);
                        var account_id = "#account_id_"+target;
                        var account_name = "#account_name_"+target;
                        transaction_categories_id[data.values.id] = data.values.account_code;
                        // $(account_id).append('<option value="'+data.values.id+'" selected>'+data.values.account_code+'</option>');
                        $(account_id).val(data.values.id);
                        $(account_id).trigger('change.select2');

                        $(account_name).append('<option value="'+data.values.id+'" selected>'+data.values.display_name+'</option>');
                        $(account_name).val(data.values.id);
                        $(account_name).trigger('change.select2');
                        $('#account_type_name').val('');
                        $('#parent_id').val('').trigger('change.select2');
                        $('#myModal').modal('toggle');
                    }else{
                        alert("Unable to Save");
                    }
                    console.log(data);

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Request failed. Show error message to user.
                    // errorThrown has error message, or "timeout" in case of timeout.
                    var error = $.parseJSON(jqXHR.responseText);
                    // console.log(error);
                    alert("Account Name is required and Unique");
                    $('#account_type_name').val('');
                    $('#parent_id').val('').trigger('change.select2');
                    // alert(textStatus);
                    // alert(errorThrown);
                });

                e.preventDefault();
            });

        });

    </script>

@endpush
