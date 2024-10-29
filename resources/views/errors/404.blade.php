@extends('errors::error-layout')

@section('code', '404')
@section('title', __('Page Not Found'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/404.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __('errors.sorry_the_page_you_are_looking_for_could_not_be_found'))
