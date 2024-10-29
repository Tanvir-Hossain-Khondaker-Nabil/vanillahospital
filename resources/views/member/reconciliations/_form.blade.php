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
            font-size: 14px;
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
    <input type="hidden" name="reconciliation_type" value="{{ $type }}"/>
    <div class="col-md-3">
        <label for="cash_or_bank_id"> Cash and Bank Account Name</label>
        @if(isset($cash_or_banks))
        <select required name="cash_or_bank_id" id='cash_or_bank_id' class='form-control select2' placeholder='Select Account'>
            <option value="">Please select Cash and Bank Account Name</option>
            @foreach( $cash_or_banks as $key => $value)
            <option value="{{$key}}">{{ $value }}</option>
            @endforeach
        </select>
        @else
            <select required name="sharer_id" id='supplier_id' class='form-control select2' placeholder='Select Account'>
                <option value="">Please select {{ $type }} Name</option>
                @foreach( $suppliers as $key => $value)
                    <option value="{{$key}}">{{ $value }}</option>
                @endforeach
            </select>
        @endif

    </div>
    <div class="col-md-3">
        <label for="date"> Date </label>
        {!! Form::text('date',null,['id'=>'date','class'=>'form-control','autocomplete'=>'off','placeholder'=>'Enter Date', 'required']); !!}
    </div>
    <div class="col-md-4">
        <label class="col-md-12 pl-0" for="cash_or_bank_id"> Reconciliation Type </label>
        <div class="col-md-4">
            <input type="radio" name="reconciliation_type" value="cr"> Add
        </div>
        <div class="col-md-8">
            <input type="radio" name="reconciliation_type" value="dr"> Deduct
        </div>
    </div>
</div>

</div>


<div class="box main-box">
    <div class="box-body">
        <div class="col-md-1">
            <label for="category_id">Account Code </label>
            {!! Form::text('account_type[]', null,['id'=>'account_id_0', 'data-option'=>'0','class'=>'form-control acc_id', 'placeholder'=>'Select Account Code']); !!}
        </div>
        <div class="col-md-3">
            <label for="category_id"> Account of Reconciliation </label>
            {!! Form::select('account_type_id[]', $transaction_categories, null,['id'=>'account_name_0', 'data-option'=>'0', 'class'=>'form-control acc_name select2 acc_name','required', 'placeholder'=>'Please Select']); !!}
        </div>
        <div class="col-md-3">
            <label for="amount"> Amount </label>
            {!! Form::text('amount[]',null,['id'=>'amount','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"0.000", 'required']); !!}
        </div>
        <div class="col-md-3">
            <label for="description">Description  </label>
            {!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}
        </div>
        <div class="col-md-1">
            <a class="btn btn-success add-row pull-right margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a>
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


                var html = '<div class="box-body">'+
                    '<div class="col-md-1">'+
                    '<label for="category_id">Account Code </label>'+
                    '<input placeholder="Select Account Code" id="account_id_'+i+'" data-option="'+i+'" class="form-control acc_id" name="account_type[]"/>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                    '<label for="category_id">Account Name </label>'+
                    '<select id="account_name_'+i+'" data-option="'+i+'" class="form-control acc_name select2 acc_name" name="account_type_id[]" required>'+optionName+
                    '</select></div>'+
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

        });

    </script>

@endpush





