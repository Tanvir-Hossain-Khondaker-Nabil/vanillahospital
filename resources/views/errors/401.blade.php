@extends('errors::error-layout')

@section('code', '401')
@section('title', __('Unauthorized'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __('errors.sorry_you_are_not_authorized_to_access_this_page'))
