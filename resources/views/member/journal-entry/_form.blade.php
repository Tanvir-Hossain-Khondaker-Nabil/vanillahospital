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
<input type="hidden" id="total-row" name="total_row" value="1"/>

<div class="box-group head-trans">

    <div class="col-md-3">
        <label for="date">{{__('common.date')}} </label>
        {!! Form::text('date',null,['id'=>'date','class'=>'form-control','autocomplete'=>'off','placeholder'=>trans('common.enter_date'), 'required']); !!}
    </div>
    <div class="col-md-3">
        <label for="date">{{__('common.event_date')}} </label>
        {!! Form::text('event_date',null,['class'=>'form-control date','autocomplete'=>'off','placeholder'=>trans('common.enter_event_date')]); !!}
    </div>

    <div class="col-md-3">
        <label for="date"> {{__('common.document_date')}}</label>
        {!! Form::text('document_date',null,['class'=>'form-control date','autocomplete'=>'off','placeholder'=>trans('common.enter_document_date')]); !!}
    </div>
    <div class="col-md-3">
        <label for="date"> {{__('common.source_reference')}}</label>
        {!! Form::text('source_reference',null,['class'=>'form-control','autocomplete'=>'off','placeholder'=>trans('common.enter_source_reference')]); !!}
    </div>
</div>


<div class="box journal-entry-box">
    <div class="box-body">
        <div class="col-md-1">
            <label for="category_id">{{__('common.account_code')}} </label>
            {!! Form::text('account_type[]', null,['id'=>'account_id_0', 'data-option'=>'0','class'=>'form-control acc_id', 'placeholder'=>trans('common.select_account_code')]); !!}
        </div>
        <div class="col-md-3">
            <label for="category_id">{{__('common.account_name')}}  </label>
            {!! Form::select('account_type_id[]', $transaction_categories, null,['id'=>'account_name_0', 'data-option'=>'0', 'class'=>'form-control acc_name select2 acc_name','required', 'placeholder'=>trans('common.select_account_name')]); !!}
            {{--            <button class="btn btn-primary btn-at"  data-toggle="modal" data-target="#myModal" data-option="0" type="button"><i class="fa fa-plus-square-o"></i> Add Account Name</button>--}}
        </div>
        <div class="col-md-2" >
            <label for="category_id">{{__('common.payment_method')}}  </label>
            <input type="hidden" name="payment_method_id[]" value="1">
            {!! Form::select('payment_method[]', $payment_methods, null,['id'=>'payment_method_0', 'data-option'=>'0', 'class'=>'form-control select2 payment_method_change','required']); !!}
            <div class="payment_method_addition">
            </div>
        </div>
        <div class="col-md-2" >
            <label for="category_id">{{__('common.transaction_type')}}   </label>
            {!! Form::select('transaction_type[]', [ 'dr'=>'Debit' ], null,['id'=>'transaction_type_0', 'class'=>'form-control','required']); !!}
        </div>
        <div class="col-md-1">
            <label for="amount">{{__('common.amount')}} </label>
            {!! Form::number('amount[]',null,['id'=>'amount_0','class'=>'form-control input-number','placeholder'=>trans('common.enter_amount'), 'step'=>"0.000", 'required']); !!}
        </div>
        <div class="col-md-2">
            <label for="description">{{__('common.description')}}   </label>
            {!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=> trans('common.enter_description'),'height'=>'70px !important' ]); !!}
        </div>
        <div class="col-md-1">
            {{--            <a class="btn btn-success add-row pull-right margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a>--}}
        </div>
    </div>
    <div class="box-body">
        <div class="col-md-1">
            <label for="category_id"> {{__('common.account_code')}}  </label>
            {!! Form::text('account_type[]', null,['id'=>'account_id_1', 'data-option'=>'1','class'=>'form-control acc_id', 'placeholder'=>trans('common.select_account_code')]); !!}
        </div>
        <div class="col-md-3">
            <label for="category_id">{{__('common.account_name')}} </label>
            {!! Form::select('account_type_id[]', $transaction_categories, null,['id'=>'account_name_1', 'data-option'=>'1', 'class'=>'form-control acc_name select2 acc_name','required', 'placeholder'=>trans('common.select_account_name')]); !!}
            {{--            <button class="btn btn-primary btn-at"  data-toggle="modal" data-target="#myModal" data-option="0" type="button"><i class="fa fa-plus-square-o"></i> Add Account Name</button>--}}
        </div>
        <div class="col-md-2" >
            <label for="category_id">{{__('common.payment_method')}} </label>
            <input type="hidden" name="payment_method_id[]" value="1">
            {!! Form::select('payment_method[]', $payment_methods, null,['id'=>'payment_method_0', 'data-option'=>'0', 'class'=>'form-control select2 payment_method_change','required']); !!}
            <div class="payment_method_addition">
            </div>
        </div>
        <div class="col-md-2" >
            <label for="category_id">{{__('common.transaction_type')}}  </label>
            {!! Form::select('transaction_type[]', [ 'cr'=>'Credit' ], null,['id'=>'transaction_type_1', 'class'=>'form-control','required']); !!}
        </div>
        <div class="col-md-1">
            <label for="amount">{{__('common.amount')}}  </label>
            {!! Form::number('amount[]',null,['id'=>'amount_1','class'=>'form-control input-number','placeholder'=>trans('common.enter_amount'), 'step'=>"0.000", 'required']); !!}
        </div>
        <div class="col-md-2">
            <label for="description">{{__('common.description')}} </label>
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
                <label for=file>{{__('common.attach_if_file_available')}}   </label>
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
            $('.date').datepicker('setDate', today);

            $('.select2').select2();

            var transaction_categories_id = [];
            transaction_categories_id = <?php print_r(json_encode($transaction_categories_id)); ?>;
            var transaction_categories  = <?php print_r(json_encode($transaction_categories)); ?>;
            var catgories = transaction_categories;

            var optionName;
            function transaction_category() {

                optionName='<option selected="selected" value=""> {{__('common.select_account_name')}}</option>';

                $.each( catgories, function( key, value ) {
                    optionName = optionName+'<option value="'+key+'" >'+value+'</option>';
                });

                return optionName;
            }

            var optionPM = '';
            @foreach($payment_methods as $key=>$value)
                optionPM = optionPM+'<option value="<?=$key?>" <?=$key==1 ? 'selected="selected"' : ''?> ><?=$value?></option>';
                @endforeach



            var i = 2;

            $(document).on('click', '.add-row', function () {

                optionName = transaction_category();
                var $div = $(this).parent().parent('.box-body');
                var childrenDiv = $div.children();

                if (childrenDiv.find('select').val() == "") {
                    bootbox.alert('Account Name can\'t be Empty');
                    return false;
                }

                if (childrenDiv.find('input#amount').val() == "") {
                    bootbox.alert('Amount can\'t be Empty');
                    return false;
                }


                $('#total-row').val(i);
                var payment_method = '<div class="col-md-2"><label for="payment_method_' + i + '">{{__('common.payment_method')}} </label>' +
                    '<input type="hidden" name="payment_method_id[]" value="1" ><select id="payment_method_' + i + '" data-option="' + i + '" class="form-control select2 payment_method_change" required="" name="payment[]" >'
                    + optionPM + '</select><div class="payment_method_addition form-group" >' +
                    '</div></div>';

                var trans_type = '<div class="col-md-2">'+
                    '<label for="category_id">{{__('common.transaction_type')}} </label>' +
                    '<select id="transaction_type_'+i+'" class="form-control" required="" name="transaction_type[]"><option value="dr">Debit</option><option value="cr">Credit</option></select>' +
                    '</div>';

                var html = '<div class="box-body">' +
                    '<div class="col-md-2">' +
                    '<label for="category_id">{{__('common.account_code')}} </label>' +
                    '<input placeholder="{{__('common.select_account_code')}}" id="account_id_' + i + '" data-option="' + i + '" class="form-control acc_id" name="account_type[]"/>' +
                    '</div> <div class="col-md-3">' +
                    '<label for="category_id">{{__('common.account_name')}} </label>' +
                    '<select id="account_name_' + i + '" data-option="' + i + '" class="form-control acc_name select2 acc_name" name="account_type_id[]" required>' + optionName +
                    '</select>' +
                    // '</select><button class="btn btn-primary btn-at" data-toggle="modal" data-target="#myModal"  data-option="' + i + '" type="button"><i class="fa fa-plus-square-o"></i> Add Account Name</button>' +
                    '</div>' + payment_method +trans_type+
                    '<div class="col-md-3">' +
                    '<label for="amount"> {{__('common.amount')}} </label>' +
                    '<input placeholder="{{__('common.enter_amount')}}" id="amount_' + i + '" data-option="' + i + '" class="form-control input-number" step="0.000" name="amount[]" required/>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<label for="description">{{__('common.description')}}  </label>' +
                    '{!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>trans('common.enter_description'),'height'=>'70px !important' ]); !!}' +
                    '</div>' +
                    '<div class="col-md-1"> <a class="btn btn-sm ml-1 btn-success add-row pull-right margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a><a href="#" class="btn btn-sm btn-text-danger ml-0 delete-field pull-right margin-top-23"><i class="fa fa-minus"></i></a>' +
                    ' </div>' +
                    '</div>';

                i++;

                $(this).parent().css("padding-top", '33px');
                // console.log(childrenDiv);
                childrenDiv.find('input').attr('readonly', 'readonly');
                $('#cash_or_bank_id').attr('readonly', true);
                childrenDiv.find('select').attr('readonly', true);
                var actionHtml = '<a href="#" class="btn-text-info margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a></span>';
                $(this).parent('div').html(actionHtml);

                $(".journal-entry-box").last().append(html);
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
                        bootbox.alert("Unable to Save");
                    }
                    console.log(data);

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Request failed. Show error message to user.
                    // errorThrown has error message, or "timeout" in case of timeout.
                    var error = $.parseJSON(jqXHR.responseText);
                    // console.log(error);
                    bootbox.alert("Account Name is required and Unique");
                    $('#account_type_name').val('');
                    $('#parent_id').val('').trigger('change.select2');
                    // bootbox.alert(textStatus);
                    // bootbox.alert(errorThrown);
                });

                e.preventDefault();
            });

            $('#submit').click( function () {
                // e.preventDefault();

                var totalRow = parseInt($('#total-row').val());

                var drAmount = 0;
                var crAmount = 0;
                for(i=0; i<=totalRow; i++)
                {
                    var transType = $('#transaction_type_'+i).val();
                    var amount = $('#amount_'+i).val();
                    amount = amount >0 ? parseFloat(amount) : 0;

                    if(transType=='dr'){
                        drAmount = drAmount+amount;
                    }else if(transType=='cr'){
                        crAmount = crAmount+amount;
                    }
                }

                if(drAmount != crAmount)
                {
                    var different = drAmount-crAmount;
                    var text = different < 0 ? "Dr "+different+" short" : "Cr "+different+" short";
                    bootbox.alert(" Debit and Credit amount is not same. "+text);
                    return false;
                }else{

                    if(drAmount>0)
                    {
                        $("#journal_form").submit();
                    }
                }
            });

        });

    </script>

@endpush


