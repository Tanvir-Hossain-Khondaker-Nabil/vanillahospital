@extends('errors::error-layout')

@section('code', '419')
@section('title', __('Page Expired'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __('errors.sorry_your_session_has_expired_please_refresh_and_try_again'))
