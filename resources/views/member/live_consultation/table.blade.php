<table id="vanilla_table" class="table text-nowrap table-bordered table-hover dataTable no-footer">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date</th>
            <th>Duration</th>
            <th>Doctor Name</th>
            <th>Patient Name</th>
            {{-- <th>Email</th>
            <th>Mobile Number</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Date Of Birth</th>
            <th>Blood Group</th> --}}
            <th>Action</th>


        </tr>
    </thead>
    <tbody>

        @foreach ($live_consultations as $key => $list)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $list->title }}</td>
            <td>{{ $list->date }}</td>
            <td>{{ $list->duration }}</td>
            <td>{{ $list->doctor->name }}</td>
            <td>{{ $list->patient_name }}</td>
            {{-- <td>{{ $list->patient_email }}</td>
            <td>{{ $list->patient_phone_one }}</td>
            <td>{{ $list->gender }}</td>
            <td>{{ $list->age }}</td>
            <td>{{ $list->date_of_birth }}</td>
            <td>{{ $list->blood_group }}</td> --}}


            <td>
                @if(\Auth::user()->can(['member.doctors.edit']))
                <a class="btn btn-xs btn-success" href="{{ route('member.vehicle_info.edit',$list->id) }}"><i class="fa fa-edit" title='Edit'></i>
                </a>


                @endif


                {{-- @if(\Auth::user()->can(['member.doctor_comission.show']))
                        <a class="btn btn-xs btn-success"
                            href="{{ route('member.vehicle_info.show',$list->id) }}"><i class="fa fa-eye" title='Comission Show'></i>
                </a>


                @endif --}}

                <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.vehicle_info.destroy', $list->id) }}">
                    <i class="fa fa-times"></i>
                </a>

            </td>

        </tr>
        @endforeach

    </tbody>

</table>
{{-- {{ $live_consultations->links() }} --}}
<div id="processing" class="d-none" style="position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 10px 35px !important;
    border: 1px solid #0000001e;
    font-size: 18px;
    color: #00000062;
    box-shadow: 0px 0px 20px #00000031;
    background:white;
    border-radius:5px">
    <p style="margin: 0; padding: 0;">Processing...</p>
</div>
