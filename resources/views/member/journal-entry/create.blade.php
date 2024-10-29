<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:31 PM
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
        'name' => 'Journal Entry',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Add Journal Entry',
    'title'=> 'Add Journal Entry',
    'heading' => trans('common.add_journal_entry'),
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
                    <h3 class="box-title"> {{__('common.journal_entry')}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.journal_entry.store','method' => 'POST', 'files'=>true, 'id'=>'journal_form', 'role'=>'form' ]) !!}

                <div class="box-body">

                    @include('member.journal-entry._form')

                    <div class="box-footer">
                        <div class="col-md-12">
                             <button type="submit" id="submit" class="btn btn-primary">{{__('common.submit')}}</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

                {!! Form::close() !!}
                <!-- /.box -->
            </div>
        </div>
    </div>

    @include('member.transaction._account_type_add');
@endsection

