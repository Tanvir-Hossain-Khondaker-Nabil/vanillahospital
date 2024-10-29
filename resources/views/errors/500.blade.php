@extends('errors::error-layout')

@section('code', '500')
@section('title', __('Error'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/500.svg') }});"
         class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __('errors.whoops_something_went_wrong_on_our_servers'))
