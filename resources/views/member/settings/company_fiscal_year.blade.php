<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/20/2019
 * Time: 4:15 PM
 */

 $route =  \Auth::user()->can(['member.users.index']) ? route('member.users.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Company Fiscal Year Setting',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Company Fiscal Year Setting',
    'title'=>'Attach Company and Fiscal Year',
    'heading' => 'Attach Company and Fiscal Year',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')

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
                    <h3 class="box-title">Attach Company and Fiscal Year</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.settings.set_company_fiscal_year', 'method' => 'POST', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_id">Company </label>
                            {!! Form::select('company_id',$companies,null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                        </div>
                        <div class="form-group">
                            <label for="fiscal_year_id">Fiscal Year </label>
                            {!! Form::select('fiscal_year_id',$fiscal_year,null,['id'=>'fiscal_year_id','class'=>'form-control select2','placeholder'=>'Select Fiscal Year']); !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success form-group"> Save </button>
                    </div>
                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Current Company Fiscal Years</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12">
                        <table class="table table-response table-bordered">
                            <tr>
                                <th> Company Name </th>
                                <th> Fiscal Year </th>
                                <th> Status </th>
                            </tr>
                            @foreach($company_fiscal_years as $company)
                            <tr>
                                <td>{{ $company->company_name }}</td>
                                <td>{{ isset($company->fiscal_year->title) ? $company->fiscal_year->fiscal_year_details : '' }}</td>
                                <td> <label class="label label-primary">Active</label></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-right"> {{ $company_fiscal_years->links() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')

     <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(function () {
            $('.select2').select2()
        });

    </script>

@endpush
