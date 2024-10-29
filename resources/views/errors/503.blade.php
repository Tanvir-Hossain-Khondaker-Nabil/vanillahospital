@extends('errors::error-layout')

@section('code', '503')
@section('title', __('Service Unavailable'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/503.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __($exception->getMessage() ?: 'errors.sorry_we_are_doing_some_maintenance_please_check_back_soon'))
