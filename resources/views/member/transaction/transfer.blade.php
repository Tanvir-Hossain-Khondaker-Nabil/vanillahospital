<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 5:44 PM
 */

 $route =  \Auth::user()->can(['member.transaction.index']) ? route('member.transaction.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Transaction',
        'href' => $route,
    ],
    [
        'name' => 'Transfer',
    ],
];

$data['data'] = [
    'name' => 'Transfer',
    'title'=> 'Transfer',
    'heading' => 'Transaction: '.'Transfer',
];

?>
@extends('layouts.back-end.master', $data)



@push('styles')

    <style type="text/css">
        textarea.form-control{
            height: 108px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Transfer </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.transaction.transfer.save','method' => 'POST','files'=>'true','role'=>'form' ]) !!}

                <div class="box-body">

                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                 <label for="from_cash_or_bank_id"> From Account </label>
                                {!! Form::select('from_cash_or_bank_id', $accounts, null,['id'=>'from_cash_or_bank_id','class'=>'form-control select2','placeholder'=>'Select Account']); !!}
                            </div>
                            <div class="form-group">
                                <label for="amount"> Amount </label>
                                {!! Form::text('amount',null,['id'=>'amount','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'required']); !!}
                            </div>
                            <div class="form-group">
                                <label for="payment_method_id"> Payment Method  </label>
                                {!! Form::select('payment_method_id', $payment_methods, null,['id'=>'payment_method_id','class'=>'form-control select2','placeholder'=>'Select Payment Method']); !!}
                            </div>
                            <div class="form-group payment_method_addition">

                            </div>
                            <div class="form-group">
                                <label for="reference"> Ref#  </label>
                                {!! Form::text('reference',null,['id'=>'reference','class'=>'form-control','placeholder'=>'Enter Ref# (Transaction No/Check No)']); !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_cash_or_bank_id"> To Account </label>
                                {!! Form::select('to_cash_or_bank_id', $accounts, null,['id'=>'to_cash_or_bank_id','class'=>'form-control select2','placeholder'=>'Select Account']); !!}
                            </div>
                            <div class="form-group">
                                <label for="date"> Date </label>
                                {!! Form::text('date',null,['id'=>'date','class'=>'form-control','placeholder'=>'Enter Date', 'required']); !!}
                            </div>
                            <div class="form-group">
                                <label for="description">Description  </label>
                                {!! Form::textarea('description',null,['id'=>'description','class'=>'form-control','placeholder'=>'Enter Description' ]); !!}
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>
@endsection



@push('scripts')

    <!-- Date range picker -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

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

            $('.select2').select2();

            var $cash_or_bank = <?php print_r(json_encode($accounts)); ?>;

            $('#from_cash_or_bank_id').change( function (e) {
                e.preventDefault();

                var val = $(this).val();
                var optionName='<option selected="selected" value="">Select Account</option>';
                var disable='';

                $.each( $cash_or_bank, function( key, value ) {

                    if(key==val)
                        disable = "disabled='disabled'";
                    else
                        disable = '';

                    optionName = optionName+'<option value="'+key+'" '+disable+' >'+value+'</option>';
                });

                $("#to_cash_or_bank_id").html(optionName);

                // $("#to_cash_or_bank_id").find("option [value="+value+"]").attr('disabled', 'disabled');
                // $("select#to_cash_or_bank_id").find("option [value="+value+"]").prop("disabled", true);
                // $('select option:selected').attr('disabled','disabled');
                //
                $('#to_cash_or_bank_id').select();
            });


            $(document).on('change','#payment_method_id', function(e){
                e.preventDefault();
                var selectedValue = $(this).find("option:selected").val();


                var value = $(this).find("option:selected").text().toLowerCase();
                var html = '<input class="form-control" name="issuer_name_0" value="" required placeholder="Issuer Name" />';

                if(value=='check')
                {
                    html = html+'<input class="form-control" required name="check_number_0" value="" placeholder="Check Number" />'
                        +'<input class="form-control" required name="receiver_name_0" value="" placeholder="Receiver Name" />'
                        +'<input class="form-control date issue_date" required name="issue_date_0" value="" placeholder="Issue Date" />'
                        +'<input class="form-control date" name="pass_date_0" required value="" placeholder="Pass Date" />'
                        +'<input class="form-control date" name="provide_date_0" required value="" placeholder="Provide Date" />';

                }else if(value=='paypal') {
                    html = html+'<input class="form-control" name="payby_email_0" required value="" placeholder="PayPal Email" type="email" />'
                }else if(value=='bkash'){
                    html = html+'<input class="form-control" name="mobile_number_0"  required value="" placeholder="Bkash Number" />'
                }else if(value=='credit card'){
                    html = html+'<input class="form-control" name="card_number_0" required value="" placeholder="Credit Card Number"  />'
                        +'<input class="form-control date" name="expire_date_0" required value="" placeholder="Expire Date" />';
                }else if(value=='master card'){
                    html = html+'<input class="form-control" name="card_number_0" required value="" placeholder="Master Card Number"  />'
                        +'<input class="form-control date" name="expire_date_0" required value="" placeholder="Expire Date" />';

                }


                $('.date').datepicker();
                $('.issue_date').datepicker({
                    "format": 'mm/dd/yyyy',
                    "endDate": "+0d",
                    "todayHighlight": true,
                    "autoclose": true
                });

                $('.payment_method_addition').html(html);

            });
        });

    </script>

@endpush
