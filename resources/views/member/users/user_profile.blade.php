<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Personal Infomation',
    ]
];

$data['data'] = [
    'name' => 'Personal Infomation',
    'title' => 'Personal Infomation',
    'heading' => 'Personal Infomation',
];

?>

@extends('layouts.back-end.master', $data)
@push('style')
<style>
    span.text-muted {
    font-weight: 700;
    color: black;
}
.d-flex{
    display: flex;
}
.row .d-flex{
    flex-wrap: wrap;
}

    .list-group-item span{
        text-transform: capitalize;
    }
</style>
@endpush
@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-3 ">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle"
                                src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo : asset('public/adminLTE/dist/img/user2-160x160.jpg') }}"
                                alt="User profile picture">
                            {{-- <img class="profile-user-img img-responsive img-circle" src="{{$profile->profile_photo ?$profile->profile_photo : asset('/public/adminLTE/dist/img/avatar5.png')}}" alt="User profile picture"> --}}

                            <h3 class="profile-username text-center">{{ $profile->uc_full_name }}</h3>
                            {{-- {{dd($profile)}} --}}
                            <p style="font-size: 13px;" class="text-muted text-center">{{ ucwords($profile->employee->designation?$profile->employee->designation->name : "") }}</p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <i class="fa fa-envelope" aria-hidden="true"></i> <span class="text-muted">Employee ID</span>
                                    <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee->employeeID }}</p>
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-envelope" aria-hidden="true"></i> <span class="text-muted">Email</span>
                                    <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->email }}</p>
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-phone-square" aria-hidden="true"></i> <span class="text-muted">Phone</span>
                                    <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->phone }}</p>
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-calendar" aria-hidden="true"></i> <span class="text-muted">Date of birth</span>
                                    <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee->dob }}</p>
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-calendar" aria-hidden="true"></i> <span class="text-muted">Join Date</span>
                                    <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee->join_date }}</p>
                                </li>
                                <li class="list-group-item">
                                    <i class="fa fa-address-book" aria-hidden="true"></i> <span class="text-muted">Position</span>
                                    <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ ucwords($profile->employee->designation? $profile->employee->designation->name : " ") }}</p>
                                </li>



                            </ul>
                            <div class="d-flex mt-4 float-left">

                            <a href="{{ route('member.personal_info.edit', \Auth::user()->employee->id) }}" class="modal-btn btn btn-primary btn-sm  ms-2">
                                <i class="fa-solid fa-pen-to-square me-1"></i>
                                <span>Edit </span>
                            </a>
                        </div>
                        </div>

                    </div>

                </div>

                <div class="col-md-9">
                        <div class="row d-flex">

                            <div class="col-md-6 d-flex">
                                <div class="box box-primary">
                                    <div class="box-body">
                                        <div class="body-title">
                                            <h4>Address Details</h4>

                                        </div>
                                        <ul class="list-group list-group-unbordered">

                                            <li class="list-group-item">
                                                <i class="fa fa-map-marker text-dark" aria-hidden="true"></i> <span class="text-muted">Present Address</span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ ucwords($profile->employee? $profile->employee->address : '') }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i> <span class="text-muted">Permanent Address</span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee? $profile->employee->address2 : '' }}</p>
                                            </li>

                                            <li class="list-group-item">
                                                <i class="fa fa-globe" aria-hidden="true"></i> <span class="text-muted">Nationality</span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ ucwords($profile->employee->country? $profile->employee->country->countryName : "") }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-thumb-tack" aria-hidden="true"></i> <span class="text-muted">State</span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee->division? $profile->employee->division->name : '' }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-adjust" aria-hidden="true"></i> <span class="text-muted"> City</span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee->district? $profile->employee->district->name : '' }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-cube" aria-hidden="true"></i> <span class="text-muted">Area</span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee->area? $profile->employee->area->name: '' }}</p>
                                            </li>


                                        </ul>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6 d-flex">
                                <div class="box box-primary">
                                    <div class="box-body">
                                        <div class="body-title">
                                            <h4>Others Information</h4>
                                        </div>
                                        <ul class="list-group list-group-unbordered">
                                            <li class="list-group-item">
                                                <i class="fa fa-ticket" aria-hidden="true"></i> <span class="text-muted">Passport Number </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee?  $profile->employee->passport_number : "" }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> <span class="text-muted"> Passport Expire </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee?  $profile->employee->passport_expire : "" }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-id-card-o" aria-hidden="true"></i> <span class="text-muted">Diving license </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee? $profile->employee->diving_license : '' }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> <span class="text-muted">Visa Expire </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee? $profile->employee->visa_expire : '' }}</p>
                                            </li>

                                            <li class="list-group-item">
                                                <i class="fa fa-ticket" aria-hidden="true"></i> <span class="text-muted">PR Number </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee? $profile->employee->pr_number : "" }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> <span class="text-muted"> PR Expire </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ $profile->employee?  $profile->employee->pr_expire: "" }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-building" aria-hidden="true"></i> <span class="text-muted">Insurance Company </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{ ucwords($profile->employee? $profile->employee->insurance_company : '') }}</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> <span class="text-muted">Insurance Number  </span>
                                                <p style="font-size: 13px;" class="text-muted  pl-4 mt-2 mb-0">{{$profile->employee? $profile->employee->insurance_number : '' }}</p>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>


                            </div>
                </div>

                </div>














            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });

        });
    </script>
@endpush
