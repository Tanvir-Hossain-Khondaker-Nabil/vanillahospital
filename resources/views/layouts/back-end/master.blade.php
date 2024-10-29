<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 11:41 AM
 */


$sidebar = \Session::get('left_sidebar', 'on');

$menu = \Session::get('sidebar_menu', 'accounts');
//   dd($menu);
if (\Request::url() === route('member.sales.pos_create'))
    $sidebar = "off"
?>

    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset($data['title'])? $data['title']: ""}} - {{  human_words(config('app.name')) }} | Accounting </title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @include('layouts.back-end.partials.styles')
    @stack('style')
</head>
<body class="hold-transition skin-blue sidebar-mini {{ $sidebar == 'off' ? 'sidebar-collapse' : ''}}">

{{-- ---Start manage Label--- --}}

<div class="modal fade" id="showLabelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    @include('common.manage_label')
</div>



<style>
    .d-none {
        display: none;
    }
</style>
{{-- ---End manage Label--- --}}

<div class="wrapper">

{{--@include('layouts.back-end.partials.header')--}}
@include('layouts.back-end.partials.new_header')

@if(\Illuminate\Support\Facades\Auth::user()->hasRole(['user']))

    @include('layouts.back-end.partials.sidebars.employee-user-sidebar')

@elseif(\Illuminate\Support\Facades\Auth::user()->hasRole(['project_manager']))

    @include('layouts.back-end.partials.sidebars.project-manager-sidebar')

@elseif(\Illuminate\Support\Facades\Auth::user()->hasRole(['sales_man']))

    @include('layouts.back-end.partials.sidebars.sales_man-sidebar')

@elseif(\Illuminate\Support\Facades\Auth::user()->hasRole(['dealer']))

    @include('layouts.back-end.partials.sidebars.dealer-sidebar')

@else

    @if($menu == "accounts")
        @include('layouts.back-end.partials.sidebars.accounts')
    @elseif($menu == "inventory")
        @include('layouts.back-end.partials.sidebars.inventory')
    @elseif($menu == "warehouse")
        @include('layouts.back-end.partials.sidebars.warehouse')
    @elseif($menu == "requisition")
        @include('layouts.back-end.partials.sidebars.requisition')
    @elseif($menu == "quotation")
        @include('layouts.back-end.partials.sidebars.quotation')
    @elseif($menu == "project")
        @include('layouts.back-end.partials.sidebars.projects')
    @elseif($menu == "repair")
        @include('layouts.back-end.partials.sidebars.repair')
    @elseif($menu == "hr")
        @include('layouts.back-end.partials.sidebars.hr')
    @elseif($menu == "reports")
        @include('layouts.back-end.partials.sidebars.reports')
    @elseif($menu == "settings")
        @include('layouts.back-end.partials.sidebars.settings')
    @else
        @include('layouts.back-end.partials.left-sidebar')
    @endif


@endif


<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

    @include('layouts.back-end.partials.breadcrumb')

    <!-- Main content -->
        <section class="content">

            @yield('contents')

        </section>
    </div>

    @include('layouts.back-end.partials.footer')

    {{--@include('layouts.back-end.partials.right-sidebar')--}}

</div>

@include('layouts.back-end.partials.scripts')

</body>
</html>
