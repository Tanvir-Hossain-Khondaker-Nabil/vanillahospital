@extends('errors::error-layout')

@section('code', '403')
@section('title', __('Forbidden'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __($exception->getMessage() ?: 'errors.sorry_you_are_forbidden_from_accessing_this_page'))
