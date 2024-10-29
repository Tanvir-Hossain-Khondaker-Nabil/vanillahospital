@extends('errors::error-layout')

@section('code', '429')
@section('title', __('Too Many Requests'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __('errors.sorry_you_are_making_too_many_requests_to_our_servers'))
