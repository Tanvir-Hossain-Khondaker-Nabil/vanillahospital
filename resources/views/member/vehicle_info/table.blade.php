
<table id="vanilla_table" class="table text-nowrap table-bordered table-hover dataTable no-footer">
    <thead>
        <tr>
            <th>ID</th>
            <th>Model No</th>
            <th>Model Year</th>
            <th>Gate Pass Year</th>
            <th>Chassis No</th>
            <th>Engine No</th>
            <th>Vehicle Document</th>
            <th>Status</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>

        @foreach ($vehicle_infos as $key => $list)
        @php
        $vehicle_info_count = App\Models\VehicleDetail::where('vehicle_info_id',$list->id)->count();
        @endphp
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $list->model_no }}</td>
            <td>{{ $list->model_year }}</td>
            <td>{{ $list->gate_pass_year }}</td>
            <td>{{ $list->chassis_no }}</td>
            <td>{{ $list->engine_no }}</td>
            <td>
                <label class="label {{@$list->status == 1 ? 'label-primary' : 'label-warning'}}  ">{{@$list->status == 1 ? 'Active' : 'Inactive'}}</label>
            </td>
            <td>{{ $list->vehicle_doc }}</td>



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
                @if ($vehicle_info_count < 1) @if(\Auth::user()->can(['member.doctors.destroy']))

                    <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.vehicle_info.destroy', $list->id) }}">
                        <i class="fa fa-times"></i>
                    </a>

                    @endif
                    @endif
            </td>

        </tr>
        @endforeach

    </tbody>

</table>
{{ $vehicle_infos->links() }}
<div id="processing" class="d-none"  style="position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 15px 70px !important;
    border: 1px solid #0000001e;
    font-size: 18px;
    color: #00000062;
    box-shadow: 0px 0px 20px #00000031;
    background:white;
    border-radius:5px">
    <p style="margin: 0; padding: 0;">Processing...</p>
</div>