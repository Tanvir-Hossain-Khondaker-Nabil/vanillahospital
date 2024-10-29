@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush
<div class="modal fade" id="addClientModal" tabindex="-1" role="dialog"
aria-labelledby="exampleModalLongTitle" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 900px">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Client</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            {{-- {!! Form::open(['route' => 'member.client.store','method' => 'POST','files'=>'true','role'=>'form' ]) !!} --}}
            <form id="serializeForm" action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="box-body row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Company Name <span class="text-red"> * </span> </label>
                            {!! Form::select('quotationer_id', $companies, null, [
                                'id' => 'quotationer_id',
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Company Name',
                                'required',
                            ]) !!}
                            <input type="hidden" id="add-from-project" name="add_from_project"
                                value="1">
                        </div>

                        <div class="form-group">
                            <label for="name">Address One <span class="text-red"> * </span></label>
                            {!! Form::text('address_1', null, [
                                'id' => 'address_1',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Address One',
                                'required',
                            ]) !!}
                        </div>


                        <div class="form-group">
                            <label for="status">Country <span class="text-red"> * </span> </label>
                            {!! Form::select('country_id', $countries, $country_id ?? null, [
                                'id' => 'country_id',
                                'class' => 'form-control select2 select-region',
                                'placeholder' => 'Please select',
                                'required',
                                'onchange' => 'showSateByCountryId(this,`addClient`)',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            <label for="status">City </label>
                            {!! Form::select('district_id', $districts, $district_id ?? null, [
                                'id' => 'district_id',
                                'class' => 'district_id form-control select2 select-district-thana',
                                'placeholder' => 'Please select',
                                'onchange' => 'showAreaByCityId(this,`addClient`)',
                            ]) !!}
                        </div>


                        <div class="form-group">
                            <label for="name">Phone Number One <span class="text-red"> * </span></label>
                            {!! Form::number('phone_1', null, [
                                'id' => 'phone_1',
                                'class' => 'form-control',
                                'placeholder' => 'Enter  Phone Number One',
                                'required',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            <label for="name">Facebook Link </label>
                            {!! Form::url('facebook', null, [
                                'id' => 'facebook',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Facebook Link ',
                            ]) !!}
                        </div>


                        <div class="form-group">
                            <label for="name">Linkedin Link </label>
                            {!! Form::url('linkedin', null, [
                                'id' => 'linkedin',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Linkedin Link ',
                            ]) !!}
                        </div>


                        <div class="form-group">
                            <label for="client_image">Client Image </label>
                            {{-- {!! Form::file('client_image',null,['id'=>'logo','class'=>'form-control','placeholder'=>'Enter Client Image']); !!}  <br/> --}}
                            <input type="file" id='client_image' class="form-control" accept="image/*"
                                name="client_image" placeholder="Enter Client Image"
                                onchange="getImagePreview(this)">
                            <input type="hidden" id="front-image-url" value="">
                            <div class="py-1" id="front-image-preview"
                                style="display: flex; gap: 10px;flex-wrap: wrap">
                            </div>

                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name <span class="text-red"> * </span></label>
                            {!! Form::text('name', null, [
                                'id' => 'name',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Name',
                                'required',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            <label for="name">Address Two </label>
                            {!! Form::text('address_2', null, [
                                'id' => 'address_2',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Address Two ',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            <label for="status">State <span class="text-red"> * </span> </label>
                            {!! Form::select('division_id', $divisions, $division_id ?? null, [
                                'id' => 'division_id',
                                'class' => 'division_id form-control select2 select-region',
                                'placeholder' => 'Please select',
                                'required',
                                'onchange' => 'showCityByStateId(this,`addClient`)',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            <label for="status">Area </label>
                            {!! Form::select('area_id', $areas, $area_id ?? null, [
                                'id' => 'area_id',
                                'class' => 'area_id form-control select2',
                                'placeholder' => 'Please select',
                            ]) !!}
                        </div>

                        <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
                            <label for="label">Label </label>
                            {{ Form::select('label_id[]', $client_label, $labeling ?? [], ['class' => 'select2 form-control', 'multiple' => 'multiple']) }}
                        </div>

                        <div class="form-group">
                            <label for="name">Phone Number Two </label>
                            {!! Form::number('phone_2', null, [
                                'id' => 'phone_2',
                                'class' => 'form-control',
                                'placeholder' => 'Enter  Phone Number Two',
                            ]) !!}
                        </div>


                        <div class="form-group">
                            <label for="name">Instagram Link </label>
                            {!! Form::url('instagram', null, [
                                'id' => 'instagram',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Instagram Link ',
                            ]) !!}
                        </div>

                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label for="status">Select Status </label>
                            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, [
                                'id' => 'status',
                                'class' => 'form-control',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            <label for="card_image">Client Visiting Card Image </label>
                            {{-- {!! Form::file('card_image',null,['id'=>'card_image','class'=>'form-control','placeholder'=>'Enter Client Visiting Card Image']); !!}  <br/> --}}
                            <input type="file" id='client_card_image' class="form-control" accept="image/*"
                                name="card_image" placeholder="Enter Client Visiting"
                                onchange="getImagePreview(this)">
                            <input type="hidden" id="front-image-url" value="">
                            <div class="py-1" id="front-image-preview-2"
                                style="display: flex; gap: 10px;flex-wrap: wrap"></div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="button" onclick="saveClient(this)" class="btn btn-primary">Submit</button>
                            {{-- <button type="submit"  class="btn btn-primary">Submit</button> --}}
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
</div>
@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

@endpush
