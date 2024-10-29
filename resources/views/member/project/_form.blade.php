<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/21/2019
 * Time: 3:55 PM
 */

if (isset($modal)) {
    $project_image = $modal->image != '' ? $modal->project_image_path : '';
} else {
    $project_image = '';
}
// dd($project_image);
?>
<style>
    .add-client-div {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 22px;
    }

    .d-flex {
        display: flex;
    }
</style>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush


<div class="col-md-6">

    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">Project Type <span class="text-red"> * </span></label>
        {!! Form::select(
            'project_status',
            ['direct' => 'Direct Project', 'lead' => 'Lead Project'],
            $project_status ?? null,
            [
                'id' => 'project_type',
                'placeholder' => 'Select Project Type',
                'class' => 'form-control',
                'required',
                'onchange' => 'checkProjectType(this)',
            ],
        ) !!}
    </div>

    <div class="form-group  {{ $errors->has('project') ? 'has-error' : '' }} ">
        <label for="project">Project <span class="text-red"> * </span> </label>
        {!! Form::text('project', null, [
            'id' => 'project_name',
            'class' => 'form-control',
            'placeholder' => 'Enter project Name',
            'required',
        ]) !!}
    </div>


    <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
        <label for="employee_id">Select Project Manager <span class="text-red"> * </span> </label>

        {!! Form::select('employee_id', $employee, $employee_id ?? null, [
            'id' => 'employee_id',
            'class' => 'form-control select2',
            'placeholder' => 'Select Manager',
            'required',
        ]) !!}

    </div>

    <div class="form-group {{ $errors->has('broker_id') ? 'has-error' : '' }}">

        <label for="broker_id">Broker</label>
        {!! Form::select('broker_id', $brokers, $broker_id ?? null, [
            'id' => 'broker_id',
            'class' => 'form-control select2',
            'placeholder' => 'Select broker',
        ]) !!}
    </div>

    <div class="form-group  {{ $errors->has('commission') ? 'has-error' : '' }} ">
        <label for="expire_date">Commission (%) </label>
        {!! Form::number('commission', null, [
            'id' => 'commission',
            'class' => 'form-control',
            'min' => 0,
            'placeholder' => 'Enter Commission ',
        ]) !!}
    </div>
    <div class="form-group  {{ $errors->has('start_date') ? 'has-error' : '' }} ">
        <label for="expire_date">Start Date <span class="text-red"> * </span></label>
        {!! Form::date('start_date', null, [
            'id' => 'start_date',
            'class' => 'form-control',
            'placeholder' => 'Enter Start Date',
            'required'
        ]) !!}
    </div>

    {{-- <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }}">
        <label for="label"> Country  <span class="text-red"> * </span> </label>
        {{ Form::select('country_id', $countries, null, ['class' => 'select2 form-control','required']) }}
    </div> --}}

    <div class="form-group">
        <label for="status">Country <span class="text-red"> * </span> </label>
        {!! Form::select('country_id', $countries, $country_id ?? null, [
            'class' => 'form-control select2 select-region',
            'placeholder' => 'Please select',
            'required',
            'onchange' => 'showSateByCountryId(this)',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="city">City </label>
        {!! Form::select('district_id', $districts, $district_id ?? null, [
            'id' => 'district_id',
            'class' => 'form-control select2 select-district-thana',
            'placeholder' => 'Please select',
            'onchange' => 'showAreaByCityId(this)',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="address">Address <span class="text-red"> * </span> </label>
        {!! Form::textarea('address', null, [
            'id' => 'address',
            'class' => 'form-control',
            'placeholder' => 'Enter Address',
            'required',
            'rows' => '2',
        ]) !!}
    </div>


    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">Status <span class="text-red"> * </span> </label>
        {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, [
            'id' => 'status',
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">Progress Status <span class="text-red"> * </span> </label>
        {!! Form::select('progress_status', $progress_statuses, null, [
            'placeholder' => 'Select Progress Status',
            'id' => 'progress_status',
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>


    <div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
        <label for="label">Label </label>
        {{ Form::select('label_id[]', $label, $labeling ?? [], ['class' => 'select2 form-control', 'multiple' => 'multiple']) }}
    </div>

    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
        <label for="image">Image</label>

        {{-- {!! Form::file('image', null, [
            'id' => 'image',
            'accept' => 'image/*',
            'placeholder' => 'Import image',
            'class' => 'form-control',
            'onchange' => 'getImagePreview(this)'
        ]) !!} --}}

        <input type="file" id='image' class="form-control" accept="image/*" name="image"
            placeholder="Import image" onchange="getImagePreview(this)">
        <input type="hidden" id="front-image-url" value="">
        <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
        </div>
        @if ($project_image)
            <img class="mt-2" src="{{ $project_image }}" style="max-height: 100px; width: 100px !important;"
                alt=""> <br />
        @endif
    </div>

</div>
<div class="col-md-6">


    <div class="form-group {{ $errors->has('lead_id') ? 'has-error' : '' }}">

        <label for="">Lead <span id="lead-required" class="text-red d-none"> * </span></label>
        {!! Form::select('lead_id', $leads, $lead_id ?? null, [
            'id' => 'lead_id',
            'class' => 'form-control select2',
            'placeholder' => 'Select Lead',
            'onchange' => 'SelectLead(this)',
        ]) !!}
    </div>

    <div class="form-group {{ $errors->has('project_category_id') ? 'has-error' : '' }}">
        <label for="project_category_id">Project Category <span class="text-red"> * </span> </label>
        {!! Form::select('project_category_id', $project_categories, null, [
            'id' => 'project_category_id',
            'class' => 'form-control select2',
            'placeholder' => 'Select Project Category',
            'required',
        ]) !!}
    </div>

    <div class="d-flex form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
        <div class="w-100">
            <label for="client_id">Client <span class="text-red"> * </span> </label>
            {!! Form::select('client_id', $client, $client_id ?? null, [
                'id' => 'client_id',
                'class' => 'form-control select2',
                'placeholder' => 'Select Client',
                'required',
            ]) !!}
        </div>
        <div class="" style="margin-top: 22px">
            <button type="button" class="btn btn-primary" id="showClientForm" onclick="addClient()">+ Add</button>
        </div>
    </div>


    <div class="form-group  {{ $errors->has('price') ? 'has-error' : '' }} ">
        <label for="price">Project Value (&euro;) <span class="text-red"> * </span></label>
        {!! Form::number('price', null, [
            'id' => 'price',
            'class' => 'form-control',
            'placeholder' => 'Enter Project Price Value',
            'required',
        ]) !!}
    </div>

    <div class="form-group  {{ $errors->has('working_days') ? 'has-error' : '' }} ">
        <label for="expire_date">Total Working Days </label>
        {!! Form::number('working_days', null, [
            'id' => 'working_days',
            'class' => 'form-control',
            'min' => 0,
            'placeholder' => 'Enter Total Working Days',
        ]) !!}
    </div>

    <div class="form-group  {{ $errors->has('expire_date') ? 'has-error' : '' }} ">
        <label for="expire_date">Deadline </label>
        {!! Form::date('expire_date', null, [
            'id' => 'expire_date',
            'class' => 'form-control',
            'readonly' => 'true',
            'placeholder' => 'Enter Deadline',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="status">State <span class="text-red"> * </span> </label>
        {!! Form::select('division_id', $divisions, $division_id ?? null, [
            'id' => 'division_id',
            'class' => 'form-control select2 select-region',
            'placeholder' => 'Please select',
            'required',
            'onchange' => 'showCityByStateId(this)',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="status">Area </label>
        {!! Form::select('area_id', $areas, $area_id ?? null, [
            'id' => 'area_id',
            'class' => 'form-control select2',
            'placeholder' => 'Please select',
        ]) !!}
    </div>

    <div class="form-group  {{ $errors->has('long') ? 'has-error' : '' }} ">
        <label for="long">Longitude </label>
        {!! Form::text('long', null, ['id' => 'long', 'class' => 'form-control', 'placeholder' => 'Enter Longitude']) !!}
    </div>


    <div class="form-group  {{ $errors->has('lat') ? 'has-error' : '' }} ">
        <label for="lat">Latitude </label>
        {!! Form::text('lat', null, ['id' => 'lat', 'class' => 'form-control', 'placeholder' => 'Enter Latitude']) !!}
    </div>

    <div class="form-group">
        <label for="description">Project Description <span class="text-red"> * </span> </label>
        {!! Form::textarea('description', null, [
            'id' => 'description',
            'class' => 'form-control',
            'required',
            'placeholder' => 'Enter Project Description',
        ]) !!}
    </div>


</div>



@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">
        // var date = new Date();


        function saveClient(){
            var formData = new FormData($("#serializeForm")[0]);

               let clientImage = $("#client_image")[0].files;
               let cardImage = $("#client_card_image")[0].files;

            //    console.log('client_image',clientImage[0].name);
            //    console.log('card_image',cardImage[0].name);

                $("#client_image").val() ? formData.append('client_image',clientImage[0], clientImage[0].name) :
                    null;

                $("#client_card_image").val() ? formData.append('card_image',cardImage[0], cardImage[0].name) :
                    null;


            //    console.log('formData is',formData);
                validation();
               $.ajax({
                        url: "{{ route('member.client.store') }}",
                        method: 'POST',
                        processData: false,
                        cache: false,
                        contentType: false,
                        data: formData,

                        success: function(data) {
                            let option = '';
                            console.log('success message', data)
                            if (data.status == 200) {
                                // $('.btnSave').prop('disabled', false);
                                bootbox.alert(`${data.message}`);
                                $.each(data.client,function(key,item){
                                    option += `<option value='${key}'>${item}</option>`
                                })

                                $('#client_id').html(option);
                                // $('#client_id').val(data.client_id);

                                setTimeout(() => {
                                    $('#client_id').val(data.client_id).trigger('change');
                                }, 1000);

                            } else if(data.status == 400){
                                // $('.btnSave').prop('disabled', false);
                                bootbox.alert(`${data.message}`);
                            }
                            $('#addClientModal').modal('hide');
                        }
                    });


            }

        function addClient() {
            $('#addClientModal').modal('show')
        }

        function validation(){
            if($('#quotationer_id').val() == ''){
                   return bootbox.alert('Company name field is required');
                  }else if($('#name').val() == ''){
                    return bootbox.alert('Name field is required');
                  }else if($('#address_1').val() == ''){
                    return bootbox.alert('Address one field is required');
                  }else if($('#country_id').val() == ''){
                    return bootbox.alert('Country field is required');
                  }
                  else if($('#phone_1').val() == ''){
                    return bootbox.alert('Phone one field is required');
                  }
        }

        function checkProjectType(el) {
            // console.log('ll',$(el).val())
            let project_type = $(el).val();
            if (project_type == 'lead') {
                $('#lead-required').removeClass('d-none');
                $('#lead_id').attr('required', 'required');

            } else {
                $('#lead-required').addClass('d-none');
                $('#lead_id').removeAttr('required');
            }
        }

        function SelectLead(el) {
            let lead_id = $(el).val();

            $.ajax({
                url: "{{ route('member.showLead') }}",
                method: 'GET',
                data: {
                    'id': lead_id,
                },
                success: function(data) {
                    if (data.status == 200) {

                        $('#project_name').val(data.data.title)

                        $('#client_id').val(data.data.client.id).trigger('change');

                    }
                }
            });
        }

        $("#start_date, #working_days").change( function (){

            let working_days = $('#working_days').val();
            let date = $('#start_date').val();

            if (working_days == '') {
                $('#working_days').val(1)
                working_days = 1;
            }

            if (working_days && date) {
                $.ajax({
                    url: "{{ route('member.set_deadline') }}",
                    method: 'GET',
                    data: {
                        'date': date,
                        'working_days': working_days,
                    },
                    success: function(data) {

                        if (data.data.status == 200) {
                            $('#expire_date').val(data.data.deadline);
                        }
                    }
                });
            }
        });


        $(function() {

            $('.select2').select2();
        });

        $(function() {

            CKEDITOR.config.toolbar_MA = ['/', ['Paragraph', 'list', 'indent', 'blocks', 'align',
                'bidi', '-',
                'Format', 'Templates', 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList'
            ]];
            CKEDITOR.replace('description', {
                toolbar: 'MA'
            });
        });
    </script>
@endpush
