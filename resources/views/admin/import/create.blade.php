<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.product_import.index']) ? route('member.product_import.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Import',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Import',
    'title'=>' Import Process',
    'heading' => ' Import Process',
];

?>



@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')
        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Import</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'admin.product_import.store','method' => 'POST', 'role'=>'form', 'files'=>'true', ]) !!}

                <div class="box-body">



                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="name">Attached Product Import <span class="text-red"> * </span> </label>
                            <div class="input-group">
                                {!! Form::file('product_import_file',null,['id'=>'product_import_file','class'=>'form-control', 'required']); !!}
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.product_import.sample') }}" class="btn btn-info"> <i class="fa fa-file-excel-o"></i> Product Import Sample</a>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>


            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer Import</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'admin.sharer_import.store','method' => 'POST', 'role'=>'form', 'files'=>'true', ]) !!}

                <input type="hidden" name="sharer_type" value="Customer" />
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="name">Attached Customer Import <span class="text-red"> * </span> </label>
                            <div class="input-group">
                                {!! Form::file('sharer_import_file',null,['id'=>'sharer_import_file','class'=>'form-control', 'required']); !!}
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.sharer_import.sample_customer') }}" class="btn btn-info"> <i class="fa fa-file-excel-o"></i> Customer Import Sample</a>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>


            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Supplier Import</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'admin.sharer_import.store','method' => 'POST', 'role'=>'form', 'files'=>'true', ]) !!}

                <div class="box-body">

                    <input type="hidden" name="sharer_type" value="supplier" />
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="name">Attached Supplier Import <span class="text-red"> * </span> </label>
                            <div class="input-group">
                                {!! Form::file('sharer_import_file',null,['id'=>'sharer_import_file','class'=>'form-control', 'required']); !!}
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.sharer_import.sample') }}" class="btn btn-info"> <i class="fa fa-file-excel-o"></i> Supplier Import Sample</a>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>

{{--            <div class="box box-primary">--}}
{{--                <div class="box-header with-border">--}}
{{--                    <h3 class="box-title">Employee Import</h3>--}}
{{--                </div>--}}
{{--                <!-- /.box-header -->--}}
{{--                <!-- form start -->--}}

{{--                {!! Form::open(['route' => 'admin.employee_import.store','method' => 'POST', 'role'=>'form', 'files'=>'true', ]) !!}--}}

{{--                <div class="box-body">--}}

{{--                    <input type="hidden" name="sharer_type" value="supplier" />--}}
{{--                    <div class="col-md-7">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="name">Attached Employee Import <span class="text-red"> * </span> </label>--}}
{{--                            <div class="input-group">--}}
{{--                                {!! Form::file('import_file',null,['id'=>'import_file','class'=>'form-control', 'required']); !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="box-footer">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <button type="submit" class="btn btn-primary">Submit</button>--}}
{{--                            <a href="{{ route('admin.employee_import.sample') }}" class="btn btn-info"> <i class="fa fa-file-excel-o"></i> Employee Import Sample</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--                <!-- /.box-body -->--}}

{{--                {!! Form::close() !!}--}}
{{--                <!-- /.box -->--}}
{{--            </div>--}}

        </div>
    </div>
@endsection


