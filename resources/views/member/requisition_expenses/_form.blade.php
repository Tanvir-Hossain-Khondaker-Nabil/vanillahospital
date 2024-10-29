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


<div class="box-body pb-0">
    <div class="row pt-2">

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputPassword" >{{__('common.date')}}</label>
                {!! Form::text('date',null,['id'=>'date','class'=>'form-control','autocomplete'=>"off",'required']); !!}
            </div>
        </div>

        @if(!Auth::user()->hasRole(['user']))
        <div class="col-md-5">
            <div class="form-group ">
                <label for="inputPassword" >{{__('common.select_employee')}} </label>
                    <select class="form-control select2"  name="employee_id" >
                        <option value="">{{__('common.select_employee')}}</option>
                        @foreach($employees as $key => $value)
                            <option value="{{ $value->id }}" {{isset($requisition_expenses) && $requisition_expenses->employee_id ==  $value->id ? 'selected' : '' }}> {{ $value->employee_name_id }}</option>
                        @endforeach
                    </select>
            </div>
        </div>
        @endif

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputPassword" >{{__('common.select_expenses')}}</label>
                <div class="input-group input-group-sm">

                    <select class="form-control select2"  id="expense_id" >
                        <option value="">{{__('common.select_expenses')}}</option>
                        @foreach($expenses as $value)
                            <option value="{{$value->id}}"> {{$value->display_name}}</option>
                        @endforeach

                    </select>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-block btn-primary" id="add_to_expenses" style="height:35px;padding: 8px 20px;">{{__('common.add')}}</button>
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- /.row -->
<div class="row">


    <div class="pl-5 col-lg-9 col-md-9 col-sm-12 col-sx-12  new-table-responsive ">

        <h4>{{__('common.expenses')}} </h4>

        <table class="table table-responsive table-striped">


            <thead>
                <tr>
                    <th class="text-center" width="40px">#{{__('common.serial')}}</th>
                    <th class="text-left">{{__('common.expense_name')}}</th>
                    <th class="text-center" width="150px">{{__('common.amount')}} </th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="expenses-list">
                @if(isset($requisition_expenses))

                    @foreach( $requisition_expenses->expenseDetails as $key => $value)
                        <tr>
                            <th style='text-align:center; vertical-align: middle;'>{{ $key+1 }}</th>
                            <th  class='text-left my-auto' style='vertical-align: middle;'>
                                <input type='hidden' class='form-control' name='expense_id[]' required value='{{$value->account_type_id}}'>{{ $value->expenseType->display_name }}</th>
                            <td><input type='number' class='form-control  input-number text-center' required  width='150px' name='amount[]' value='{{$value->amount}}' /></td>
                            <td><a href='javascript:void(0)' class='btn btn-sm btn-danger delete-field'><i class='fa fa-trash'></i> </a> </td></tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
            <tr>
                <th class="text-center" colspan="2">{{__('common.total')}}  </th>
                <th class="text-center" width="150px" id="total_amount">

                    @if(isset($requisition_expenses))
                        {{ create_money_format($requisition_expenses->total_amount) }}
                    @else
                        0.00
                    @endif
                </th>
                <th></th>
            </tr>
            </tfoot>
        </table>

    </div>

</div>

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(function () {

            $('.select2').select2();

            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            var today = moment().format('MM/DD/YYYY');
            $('#date').datepicker('setDate', today);
            $('#date').datepicker('update');
            $('.date').datepicker('setDate', today);

            @if(isset($requisition_expenses))
                var expensesText = @json($requisition_expenses->expenseDetails->pluck('account_type_id')->toArray())
            @else
                var expensesText = [];
            @endif

            $("#add_to_expenses").click( function () {

                var expense_id = parseInt($("#expense_id :selected").val());

                if (expensesText.includes(expense_id)) {
                    bootbox.alert("Already Selected");
                    return false;
                }

                if(expense_id>0)
                {
                    let listTrlength = $("#expenses-list").find('tr').length+1;

                    var product_text = $("#expense_id :selected").text();
                    var unit = $("#expense_id :selected").data('value');

                    let html = "<tr><th style='text-align:center; vertical-align: middle;'>"+listTrlength+"</th>" +
                        "<th  class='text-left my-auto' style='vertical-align: middle;'>" +
                        "<input type='hidden' class='form-control' name='expense_id[]' required value='"+expense_id+"'>"+product_text+"</th>" +
                        "<td><input type='number' class='form-control  input-number text-center' required  width='150px' name='amount[]' value='' /></td>" +
                        "<td><a href='javascript:void(0)' class='btn btn-sm btn-danger delete-field'><i class='fa fa-trash'></i> </a> </td></tr>";

                    $("#expenses-list").append(html);

                    expensesText.push(expense_id);
                    // $("#expense_id option[value="+expense_id+"]").remove();

                    $(".btn.btn-success").attr('disabled',false);
                }
            });

            function checkTrSerial()
            {
                let listTrans = $("#expenses-list").find('tr');
                let listTranslength = $("#expenses-list").find('tr').length;

                for(var i=1; i<=listTranslength; i++)
                {
                    $("#expenses-list").find('tr:nth-child('+i+')').find("th:first-child").html(i);
                }
            }


            function total_amount()
            {
                let listTr = $("#expenses-list").find('tr');

                let $totalAmount = 0;
                for(var i=1; i<=listTr.length; i++)
                {
                    var $amount = $("#expenses-list").find('tr:nth-child('+i+')').find("td:nth-child(3) input").val();
                    $totalAmount += $amount == undefined || $amount == "" ? 0 :parseFloat($amount);
                }

                $("#total_amount").text($totalAmount.toFixed(2));
            }

            $(document).on('change','.input-number', function(e) {
                total_amount();
            });

            $(document).on('click','.delete-field', function(e) {
                e.preventDefault();
                var $div = $(this).parent().parent();
                var $expense_id = $div.find("th:nth-child(2) input").val();
                expensesText.splice($.inArray($expense_id, expensesText), 1);
                $div.remove();

                let listTr = $("#expenses-list").find('tr');

                if(listTr.length<1)
                {
                    $(".btn.btn-success").attr('disabled',true);
                }

                checkTrSerial();
                total_amount();
            });


        });

    </script>

@endpush
