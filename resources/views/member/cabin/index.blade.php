<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.cabin_class.index']) ? route('member.cabin_class.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Cabin',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Cabin',
    'title' => 'List Of Cabin',
    'heading' => 'List Of Cabin',
];

?>

@extends('layouts.back-end.master', $data)

@push('style')
<style>
  .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
    border: 1.5px solid #d2d6de !important;
    border-radius: 0 !important;
    padding: 6px 12px;
    height: 34px !important;
    border-right: 2px solid #00000082;
    border-bottom: 2px solid #0000009c;
}
.badge {
    background-color: #000;

}
</style>
@endpush
@section('contents')
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('member.cabin_class.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="status" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Cabin Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="company_id">Hospital Name</label>
                            <select class="custom-select select2 form-control" name="company_id" id="company_id" required="">
                                @foreach ($company as $item)
                                    <option  value="{{ $item->id }}">{{ $item->company_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2 focused">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="">Cabin Class Title</label>
                            <input type="text" id="cabin_title" name="cabin_class" class="form-control validate">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary btn-sm" id="">Add<i
                                class="fa fa-plus ml-1"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close<i
                                class="fa fa-times ml-1" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-default1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('member.cabin_class.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="status" value="2">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Cabin Sub Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="company_id">Hospital Name</label>
                            <select class="custom-select select2 form-control" onchange="getCabin(this,1)" name="company_id" id="company_id" required="">
                                <option value="0">Select Hospital</option>
                                @foreach ($company as $item)
                                    <option  value="{{ $item->id }}">{{ $item->company_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_class_id">Cabin Class</label>
                            <select class="custom-select select2 form-control" name="cabin_class_id" id="cabin_class_id" required="">
                                <option value="0">Select Cabin Class Title</option>
                                {{-- @foreach ($cabinClass as $item)
                                    <option  value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach --}}

                            </select>
                        </div>
                        <div class="md-form mb-2 focused">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_title1"> Cabin Sub Class Title</label>
                            <input type="text" id="cabin_title1" name="subClassTitle" class="form-control validate">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary btn-sm" id="">Add<i
                                class="fa fa-plus ml-1"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close<i
                                class="fa fa-times ml-1" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-default2">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('member.cabin_class.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="status" value="3">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Room</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="company_id">Hospital Name</label>
                            <select class="custom-select select2 form-control" onchange="getCabin(this,1)" name="company_id" id="company_id" required="">
                               <option value="0">Select Hospital</option>
                                @foreach ($company as $item)
                                    <option  value="{{ $item->id }}">{{ $item->company_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_class_id">Cabin Class Title</label>
                            <select class="custom-select select2 form-control cabin_class_id" onchange="getCabin(this,2)" name="cabin_class_id" id="cabin_class_id" required="">
                                <option value="0">Select Cabin Class Title</option>
                                {{-- @foreach ($cabinClass as $item)
                                    <option  value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach --}}

                            </select>
                        </div>
                        <div class="md-form mb-2">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_sub_class_id">Cabin Sub Class Title</label>
                            <select class="custom-select select2 form-control" name="cabin_sub_class_id" id="cabin_class_id" required="">
                                @foreach ($cabinSubClass as $item)
                                    <option  value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2 focused">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="room_title"> Cabin Room title</label>
                            <input type="text" id="room_title" name="room_title" class="form-control validate">
                        </div>
                        <div class="md-form mb-2 focused">
                            {{-- <i class="fa fa-medkit" aria-hidden="true"></i> --}}
                            <label class="d-block" for="price"> Price</label>
                            <input type="text" id="price" name="price" class="form-control validate">
                        </div>
                        <div class="md-form mb-2 focused">
                            {{-- <i class="fa fa-medkit" aria-hidden="true"></i> --}}
                            <label class="d-block" for="seat_capacity"> Seat Capacity</label>
                            <input type="text" id="seat_capacity" name="seat_capacity" class="form-control validate">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary btn-sm" id="">Add<i
                                class="fa fa-plus ml-1"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close<i
                                class="fa fa-times ml-1" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-update">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('member.cabin_class.update') }}" method="post">
                    @csrf
                    <input type="hidden" class="cabin_class_id" name="cabin_class_id" value="">
                    <input type="hidden" name="status" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Cabin Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="company_id">Hospital Name</label>
                            <select class="custom-select select2 form-control company_id" name="company_id" id="company_id" required="">
                                @foreach ($company as $item)
                                    <option  value="{{ $item->id }}">{{ $item->company_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2 focused">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="">Cabin Class Title</label>
                            <input type="text" id="cabin_title" name="cabin_class" class="form-control validate cabin_class">
                        </div>
                        <div class="md-form mb-2">

                            <label class="d-block" for="status1">Status</label>
                            <select class="custom-select select2 form-control status1" name="status1" id="company_id" required="">
                               <option value="0"> Hide</option>
                               <option value="1"> Show</option>

                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary btn-sm" id="">Update<i
                                class="fa fa-plus ml-1"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close<i
                                class="fa fa-times ml-1" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-update1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('member.cabin_class.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="status" value="2">
                    <input type="hidden" class="cabin_sub_class_id" name="cabin_sub_class_id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Cabin Sub Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="company_id">Hospital Name</label>
                            <select class="custom-select select2 form-control company_id" onchange="getCabin(this,1)" name="company_id" id="company_id" required="">
                                <option value="0">Select Hospital</option>
                                @foreach ($company as $item)
                                    <option  value="{{ $item->id }}">{{ $item->company_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_class_id">Cabin Class</label>
                            <select class="custom-select select2 form-control cabin_class_id" name="cabin_class_id" id="cabin_class_id" required="">
                                <option value="0">Select Cabin Class Title</option>
                                @foreach ($cabinClass as $item)
                                    <option  value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2 focused">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_title1"> Cabin Sub Class Title</label>
                            <input type="text" id="cabin_title1" name="subClassTitle" class="form-control validate cabin_title1">
                        </div>
                        <div class="md-form mb-2">

                            <label class="d-block" for="status1">Status</label>
                            <select class="custom-select select2 form-control status1" name="status1" id="company_id" required="">
                               <option value="0"> Hide</option>
                               <option value="1"> Show</option>

                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary btn-sm" id="">Update<i
                                class="fa fa-plus ml-1"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close<i
                                class="fa fa-times ml-1" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-update2">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('member.cabin_class.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="status" value="3">
                    <input type="hidden" class="room_id" name="room_id" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Room</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="md-form mb-2">
                            <i class="fa fa-h-square" aria-hidden="true"></i>
                            <label class="d-block" for="company_id">Hospital Name</label>
                            <select class="custom-select select2 form-control company_id" onchange="getCabin(this,1)" name="company_id" id="company_id" required="">
                               <option value="0">Select Hospital</option>
                                @foreach ($company as $item)
                                    <option  value="{{ $item->id }}">{{ $item->company_name }}</option>
                                @endforeach

                            </select>
                        </div> --}}
                        {{-- <div class="md-form mb-2">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_class_id">Cabin Class Title</label>
                            <select class="custom-select select2 form-control cabin_class_id" onchange="getCabin(this,2)" name="cabin_class_id" id="cabin_class_id" required="">
                                <option value="0">Select Cabin Class Title</option>
                                @foreach ($cabinClass as $item)
                                    <option  value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach

                            </select>
                        </div> --}}
                        <div class="md-form mb-2">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="cabin_sub_class_id">Cabin Sub Class Title</label>
                            <select class="custom-select select2 form-control cabin_sub_class_id" name="cabin_sub_class_id" id="cabin_sub_class_id" required="">
                                @foreach ($cabinSubClass as $item)
                                    <option  value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="md-form mb-2 focused">
                            <i class="fa fa-medkit" aria-hidden="true"></i>
                            <label class="d-block" for="room_title"> Cabin Room title</label>
                            <input type="text" id="room_title" name="room_title" class="form-control validate room_title">
                        </div>
                        <div class="md-form mb-2 focused">
                            {{-- <i class="fa fa-medkit" aria-hidden="true"></i> --}}
                            <label class="d-block" for="price"> Price</label>
                            <input type="text" id="price" name="price" class="form-control price validate">
                        </div>
                        <div class="md-form mb-2 focused">
                            {{-- <i class="fa fa-medkit" aria-hidden="true"></i> --}}
                            <label class="d-block" for="seat_capacity"> Seat Capacity</label>
                            <input type="text" id="seat_capacity" name="seat_capacity" class="form-control validate seat_capacity">
                        </div>
                        <div class="md-form mb-2">

                            <label class="d-block" for="status1">Status</label>
                            <select class="custom-select select2 form-control status1" name="status1" id="company_id" required="">
                               <option value="0"> Hide</option>
                               <option value="1"> Show</option>

                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary btn-sm" id="">Update<i
                                class="fa fa-plus ml-1"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close<i
                                class="fa fa-times ml-1" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">

                    <div class="box">
                        <div class="box-header">
                            <div class="box-header">
                                {{-- @if (\Auth::user()->can(['member.cabin_class.create']))
                                    <a href="{{ route('member.cabin_class.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                        </i>  Add Doctor</a>
                                @endif --}}
                                <button type="button" class="btn btn-info ml-2" data-toggle="modal"
                                    data-target="#modal-default">
                                    <i class="fa fa-plus">
                                    </i> Add Class
                                </button>
                                <button type="button" class="btn btn-info ml-2" data-toggle="modal"
                                    data-target="#modal-default1">
                                    <i class="fa fa-plus">
                                    </i> Add Sub Class
                                </button>
                                <button type="button" class="btn btn-info ml-2" data-toggle="modal"
                                    data-target="#modal-default2">
                                    <i class="fa fa-plus">
                                    </i> Add Room
                                </button>

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Company</th>
                                        <th>Cabin Title</th>
                                        <th>Sub Cabin Title</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($cabinClass as $key => $list)
                                        <tr>
                                            {{-- {{dd($list)}} --}}
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $list->company->company_name }}</td>
                                            <td> <button class="btn btn-success" onclick="updateCabinClass({{ $list->id }})" data-toggle="modal"
                                                data-target="#modal-update">{{ $list->title }}</button> </td>

                                            <td>
                                                @foreach ($list->subClass as $item)
                                                    <button data-toggle="modal"
                                                    data-target="#modal-update1" onclick="updateCabinSubClass({{ $item->id }})" class="btn btn-primary ml-2 ">{{ $item->title }}</button><br>
                                                    @php
                                                        $rooms = App\Models\Room::where([['cabin_sub_class_id',$item->id],['status',1]])->get();
                                                    @endphp
                                                   <div class="p-2">
                                                    @foreach ($rooms as $room)
                                                    <button data-toggle="modal"
                                                    data-target="#modal-update2" onclick="updateRoom({{ $room->id }})" class="badge badge-pill badge-secondary ml-2 ">{{ $room->title }} ( {{ $room->price.' ৳' }} ) ( {{ $room->seat_capacity.' bed' }} )</button>

                                                     @endforeach
                                                   </div>
                                                @endforeach
                                            </td>




                                            <td>

                                                @if (\Auth::user()->can(['member.cabin_class.destroy']))
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-xs btn-danger delete-confirm"
                                                        data-target="{{ route('member.cabin_class.destroy', $list->id) }}">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        function updateCabinClass(id){

            $.ajax({
                type:"Post",
                url:"{{ route('member.getCabinClass') }}",
                data:{
                    id:id,
                    _token:"{{ csrf_token() }}",
                },
                success:function(res){
                    res = res.data;
                    $("#modal-update .company_id").val(res.company_id).trigger('change');
                    $("#modal-update .cabin_class").val(res.title);
                    $("#modal-update .cabin_class_id").val(res.id);
                    $("#modal-update .status1").val(res.status).trigger('change');


                }
            })
        }
        function updateCabinSubClass(id){

            $.ajax({
                type:"Post",
                url:"{{ route('member.updateCabinSubClass') }}",
                data:{
                    id:id,
                    _token:"{{ csrf_token() }}",
                },
                success:function(res){
                    // console.log(res);
                    res = res.data;
                    $("#modal-update1 .company_id").val(res.company_id).trigger('change');
                    setTimeout(() => {
                        $("#modal-update1 .cabin_class_id").val(res.cabin_class_id).trigger('change');

                    }, 1000);
                    $("#modal-update1 .cabin_title1").val(res.title);
                    $("#modal-update1 .cabin_sub_class_id").val(res.id);
                    $("#modal-update1 .status1").val(res.status).trigger('change');

                }
            })
        }
        function updateRoom(id){

            $.ajax({
                type:"Post",
                url:"{{ route('member.updateRoom') }}",
                data:{
                    id:id,
                    _token:"{{ csrf_token() }}",
                },
                success:function(res){
                    // console.log(res);
                    res = res.data;

                        $("#modal-update2 .cabin_sub_class_id").val(res.cabin_sub_class_id).trigger('change');

                    $("#modal-update2 .price").val(res.price);
                    $("#modal-update2 .seat_capacity").val(res.seat_capacity);
                    $("#modal-update2 .room_title").val(res.title);
                    $("#modal-update2 .room_id").val(res.id);
                    $("#modal-update2 .status1").val(res.status).trigger('change');

                }
            })
        }
        function getCabin(e,status){
            let target = $(e).parent().next().find('select');
            let company_id ;
            let cabinClass = 0 ;
            if(status == 1){
                company_id = $(e).val();
            }else{
                company_id = $(e).parent().prev().find('select').val();
                cabinClass = $(e).val();
            }
            // console.log(company_id,cabinClass);
            $.ajax({
                type:"Post",
                url:"{{ route('member.getCabin') }}",
                data:{
                    company_id:company_id,
                    cabin_class_id:cabinClass,
                    _token:"{{ csrf_token() }}",
                    status:status,
                },
                success:function(res){
                    // console.log(res);
                    target.html(res);

                }
            })
        }
        $(".select2").select2();
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
