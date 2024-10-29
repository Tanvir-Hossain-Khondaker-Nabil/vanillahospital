<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$route = \Auth::user()->can(['member.project.index']) ? route('member.project.index') : "#";
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Project',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Project',
    'title' => 'Project',
    'heading' => 'Project',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')

    @include('member.project.project_analytics')

@endsection

