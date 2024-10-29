<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 11/9/2019
 * Time: 1:03 PM
 */


 $route =  \Auth::user()->can(['member.cheque_entries.index']) ? route('member.cheque_entries.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Cheque Entry',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Cheque Entry',
    'title'=>'List Of Today\'s Cheque Entry Queue',
    'heading' =>trans('common.list_of_today_cheque_entry_queue'),
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-responsive">
                                <tr>
                                    <th colspan="7" class="text-center"><h3> {{__('common.cheque_issues_for_today')}}</h3></th>
                                </tr>
                                <tr>
                                    <th>#{{__('common.serial')}}</th>
                                    <th>{{__('common.payer_name')}} </th>
                                    <th>{{__('common.banks')}} </th>
                                    <th>{{__('common.cheque_no')}}</th>
                                    <th>{{__('common.amount')}}</th>
                                    <th>{{__('common.giving_date')}}</th>
                                    <th>{{__('common.issue_date')}}</th>
                                    <th>{{__('common.manage')}}</th>

                                </tr>
                                @foreach($cheque_list as $value)
                                <tr>
                                   <td>{{ $loop->iteration	 }}</td>
                                   <td>{{ $value->payer_name }}</td>
                                   <td>{{ $value->bank->display_name }}</td>
                                   <td>{{ $value->cheque_no }}</td>
                                   <td>{{ create_money_format($value->amount) }}</td>
                                   <td>{{ db_date_month_year_format($value->giving_date) }}</td>
                                   <td>{{ db_date_month_year_format($value->issue_date) }}</td>
                                   <td>
                                       <select class="form-control issue_status" name="issue_statuses" data-id="{{ $value->id }}" {{ $value->issue_status == "completed" ? "disabled" : ''}}>
                                           @foreach($issue_statuses as $key => $val)
                                                <option value="{{ $key }}" {{ $value->issue_status == $key ? "selected" : ""}}>{{ $val }}</option>
                                           @endforeach
                                       </select>
{{--                                       <a href="{{ route('member.cheque_entries.status') }}" class="btn btn-xs btn-success"> <i class="fa fa-check-circle"></i></a>--}}
                                   </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $body = $('body');

        $body.on('change', '.issue_status', function () {
            var $this = $(this);
            bootbox.confirm("Do you want to change your issue status?" ,  function(result) {
                var id = $this.data('id');
                var status = $this.val();
                if (result){
                    $.ajax({
                        url: "{{ route('member.cheque_entries.change_status') }}",
                        type: "post",
                        data: {id:id, status: status},
                        dataType: "json",
                        success: function (data) {
                            bootbox.alert("Issue Status changed successfully",
                                function () {
                                    location.reload();
                                });
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            var response = xhr.responseJSON;
                            bootbox.alert("OOP! Sorry Response Denied");
                        }
                    });
                }else{
                    bootbox.alert("OOP! Sorry Response Denied");
                }
            });
        });
    </script>
    @endpush
