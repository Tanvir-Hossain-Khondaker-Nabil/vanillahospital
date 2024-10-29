<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */
$user = \Illuminate\Support\Facades\Auth::user();


?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

<div class="col-md-12 px-0">

    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.personal_information')}}</h3>
    </div>

    <div class="col-md-6 py-3">
        <div class="form-group">
            <label for="name">{{__('common.first_name')}}  <span class="text-red"> * </span> </label>
            {!! Form::text('first_name', null, [
                'id' => 'name',
                'class' => 'form-control',
                'placeholder' =>trans('common.first_name'),
                'required',
            ]) !!}
        </div>
        <div class="form-group">
            <label for="name">{{__('common.phone')}} <span class="text-red"> * </span> </label>
            {!! Form::text('phone', isset($model) ? $model->phone2 : null, [
                'id' => 'phone',
                'class' => 'form-control',
                'placeholder' =>trans('common.phone'),
                'required',
            ]) !!}
        </div>
        <div class="form-group">
            <label for="name">{{__('common.present_address')}} <span class="text-red"> * </span> </label>
            {!! Form::text('present_address', isset($model) ? $model->address : null, [
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_present_address'),
                'required',
            ]) !!}
        </div>
        @if (config('settings.areas'))
            <div class="form-group division area_hidden">
                <label for="status">{{__('common.nationality')}}  <span class="text-red"> * </span> </label>
                {!! Form::select('nationality', $countries, null, [
                    'class' => 'form-control select2 select-region',
                    'placeholder' =>trans('common.please_select'),
                    'required',
                    'onchange'=> 'showSateByCountryId(this)'
                ]) !!}
            </div>
            <div class="form-group district area_hidden">
                <label for="status">{{__('common.city')}}  </label>
                {{-- <select class="form-control select2" onchange="showAreaByCityId(this)" required name="district_id" id="district_id">

                </select> --}}
                {!! Form::select('district_id', $districts, null, [
                    'id' => 'district_id',
                    'class' => 'form-control select2 select-district-thana',
                    'placeholder' =>trans('common.please_select'),
                    'onchange'=> 'showAreaByCityId(this)'
                ]) !!}
            </div>
        @endif
        <div class="form-group">
            <label for="name">{{__('common.date_of_birth')}} <span class="text-red"> * </span> </label>
            {!! Form::text('dob', isset($model) ? create_date_format($model->dob, '/') : null, [
                'id' => 'dob',
                'class' => 'form-control date',
                'placeholder' =>trans('common.please_select'),
                'required',
                'autocomplete' => 'off',
            ]) !!}
        </div>

    </div>
    <div class="col-md-6 py-3">
        <div class="form-group">
            <label for="name">{{__('common.last_name')}}  <span class="text-red"> * </span> </label>
            {!! Form::text('last_name', null, [
                'id' => 'last_name',
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_last_name'),
                'required',
            ]) !!}
        </div>
        <div class="form-group">
            <label for="name">{{__('common.email')}}  <span class="text-red"> * </span> </label>
            {!! Form::text('email', isset($model) ? $model->user->email : null, [
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_email'),
                'required',
            ]) !!}
        </div>

        <div class="form-group">
            <label for="name">{{__('common.permanent_address')}} <span class="text-red"> * </span> </label>
            {!! Form::text('permanent_address', isset($model) ? $model->address2 : null, [
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_permanent_address'),
                'required',
            ]) !!}
        </div>

        @if (config('settings.areas'))
            <div class="form-group division area_hidden">
                <label for="status">{{__('common.state')}}  <span class="text-red"> * </span> </label>

                {!! Form::select('division_id', $divisions, null, [
                    'id'=>'division_id',
                    'class' => 'form-control select2 select-region',
                    'placeholder' =>trans('common.please_select'),
                    'required',
                    'onchange'=> 'showCityByStateId(this)'
                ]) !!}
            </div>
            <div class="form-group area area_hidden">
                <label for="status"> {{__('common.areas')}}  </label>

                {!! Form::select('area_id', $areas, null, [
                    'id' => 'area_id',
                    'class' => 'form-control select2',
                    'placeholder' =>trans('common.please_select'),
                ]) !!}
            </div>

            {{-- <div class="form-group region area_hidden"> --}}
            {{-- <label for="status">Select Region  </label> --}}
            {{-- {!! Form::select('region_id',$regions, null,['id'=>'region_id','class'=>'form-control select2 select-region-district','placeholder'=>'Please select']); !!} --}}
            {{-- </div> --}}

            {{-- <div class="form-group thana area_hidden"> --}}
            {{-- <label for="status">Select Thana   </label> --}}
            {{-- {!! Form::select('thana_id',$thanas, null,['id'=>'thana_id','class'=>'form-control select2','placeholder'=>'Please select']); !!} --}}
            {{-- </div> --}}
        @endif

        @if (!isset($model))
            <div class="form-group">
                <label for="password">{{__('common.password')}}  <span class="text-red"> * </span> </label>
                <input type="password" class="form-control" onfocus="this.value=''" value=""
                       autocomplete="new-password" name="password" required placeholder="{{__('common.password')}}">
            </div>
        @endif

    </div>
</div>


<div class="col-md-12 px-0">

    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.office_information')}}</h3>
    </div>

    <div class="col-md-6 py-3">


        <div class="form-group">
            <label for="password">{{__('common.employee_id')}}<span class="text-red"> * </span> </label>
            {!! Form::text('employeeID', null, [
                'id' => 'employeeID',
                'class' => 'form-control',
                'placeholder' =>trans('enter_employee_id'),
                'required',
            ]) !!}
        </div>

        <div class="form-group">
            <label for="status">{{__('common.select_department')}} <span class="text-red"> * </span> </label>
            <select class="form-control select2" onchange="selectDepartment(this)" required name="department_id"
                    id="department_id">
                <option value=""> {{__('common.select_department')}}</option>
                @foreach ($departments as $value)
                    <option value="{{ $value->id }}"
                        {{ isset($model) ? $model->department_id == $value->id ? 'selected' : '' : '' }}>
                        {{ $value->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">{{__('common.working_shift')}}  <span class="text-red"> * </span> </label>
            <select class="form-control select2" required name="shift_id" id="shift_id">
                <option value=""> {{__('common.working_shift')}}</option>
                @foreach ($shifts as $value)
                    <option value="{{ $value->id }}"
                        {{ isset($model) ? $model->shift_id == $value->id ? 'selected' : '' : '' }}>
                        {{ $value->shift_type_name . ' (' . $value->time_in_out . ') ' }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">{{__('common.salary_system')}}  <span class="text-red"> * </span> </label>
            {!! Form::select(
                'salary_system',
                get_salary_system(),
                isset($model) ? $model->salary_system : $user->company->salary_system,
                ['class' => 'form-control ', 'placeholder' =>trans('common.please_select')]
            ) !!}
        </div>

        <div class="form-group">
            <label for="name">{{__('common.commission')}}  <span class="text-red"> * </span> </label>
            {!! Form::number('commission', null, [
                'id' => 'commission',
                'class' => 'form-control',
                'step' => 'any',
                'placeholder' =>trans('common.enter_commission'),
                'required',
            ]) !!}
        </div>

    </div>
    <div class="col-md-6 py-3">

        <div class="form-group">
            <label for="initial_balance">{{__('common.join_date')}}  </label>
            {!! Form::text('join_date', isset($model) ? create_date_format($model->join_date, '/') : null, [
                'class' => 'form-control date ',
                'placeholder' =>trans('common.join_date'),
                'autocomplete' => 'off',
            ]) !!}
        </div>

        <div class="form-group">
            <label for="status"> {{__('common.select_designation')}}  <span class="text-red"> * </span> </label>
            <select class="form-control select2" required name="designation_id" id="designation_id">
                <option value="">{{__('common.select_designation')}} </option>
                @foreach ($designations as $value)
                    <option value="{{ $value->id }}" data-salary="{{ $value->salary }}"
                            data-area="{{ $value->commission_area }}"
                            data-commission="{{ $value->commission_percentage }}"
                        {{ isset($model) ? $model->designation_id == $value->id ? 'selected' : '' : '' }}>
                        {{ $value->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="name">{{__('common.weekend_accept')}} <span class="text-red"> * </span> </label>
            {!! Form::select('weekend_accept', [1 => 'Yes', 0 => 'No'], null, [
                'class' => 'form-control select2 select-weekend_accept',
                'placeholder' =>trans('common.please_select'),
            ]) !!}
        </div>
        <div class="form-group">
            <label for="name">{{__('common.salary')}}  <span class="text-red"> * </span> </label>
            {!! Form::number('salary', null, [
                'id' => 'salary',
                'class' => 'form-control',
                'step' => 'any',
                'placeholder' =>trans('common.enter_salary'),
                'required',
            ]) !!}
        </div>
    </div>

</div>

<div class="col-md-12 px-0">


    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.others_information')}}</h3>
    </div>


    <div class="col-md-6 py-3">

        <div class="form-group">
            <label for="name">{{__('common.nif_number')}} <span class="text-red"> * </span> </label>
            {!! Form::text('nid', null, [
                'id' => 'nid',
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_nif_number'),
                'required',
            ]) !!}

            <br/>

            <input name="submitted_files['nid']" type="file" accept="image/*, application/pdf" class='preview-file'
                   onchange="getImagePreview(this)"/>
            <input type="hidden" id="front-image-url" value="">

            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
                @php
                    $nid = isset($model) ? $model->getFile($model->id, "nid") : false ;
                @endphp
                @if($nid)
                    @if($nid->attached && (\Illuminate\Support\Str::contains($nid->attached_file, ['.jpg','.png','.jpeg','gif'])) )
                        <img src="{{ $nid->attached_file }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                    @else
                        <a target="_blank" class="btn btn-primary btn-sm" href="{{ $nid->attached ? $nid->attached_file : 'javascript:void(0)' }}">
                            <i class="fa fa-files-o"></i> View file </a>

                    @endif

                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="initial_balance">{{__('common.passport_expiry_date')}} </label>
            {!! Form::text('passport_expire', isset($model) ? create_date_format($model->passport_expire, '/') : null, [
                'class' => 'form-control expire-date ',
                'placeholder' =>trans('common.passport_expiry_date'),
                'autocomplete' => 'off',
            ]) !!}
        </div>


        <div class="form-group">
            <label for="name">{{__('common.diving_license')}}  </label>
            {!! Form::text('diving_license', null, [
                'id' => 'diving_license',
                'class' => 'form-control',
                'placeholder' =>trans('common.diving_license') ,
            ]) !!}


            <br/>
            <input name="submitted_files['diving_license']" type="file" accept="image/*, application/pdf"
                   class='preview-file' onchange="getImagePreview(this)"/>
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">

                @php
                    $diving_license = isset($model) ? $model->getFile($model->id, "diving_license") : false ;
                @endphp
                @if($diving_license)

                    @if($diving_license->attached && (\Illuminate\Support\Str::contains($diving_license->attached_file, ['.jpg','.png','.jpeg','gif'])) )
                        <img src="{{ $diving_license->attached_file }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                    @else
                    <a target="_blank" class="btn btn-primary btn-sm"
                       href="{{ $diving_license->attached ? $diving_license->attached_file : 'javascript:void(0)' }}">
                        <i class="fa fa-files-o"></i> {{__('common.view_file')}} </a>
                    @endif
                @endif

            </div>



        </div>


        <div class="form-group">
            <label for="initial_balance">{{__('common.diving_license_expiry_date')}} </label>
            {!! Form::text('driving_expire', isset($model) ? create_date_format($model->driving_expire, '/') : null, [
                'class' => 'form-control expire-date ',
                'placeholder' =>trans('common.diving_license_expiry_date'),
                'autocomplete' => 'off',
            ]) !!}
        </div>

        <div class="form-group">
            <label for="status">{{__('common.salary_payment_type')}} </label>
            {!! Form::select('bank_payment_type', ['own' => 'Own Account', 'other' => 'Others Account'], null, [
                'id' => 'bank_payment_type',
                'class' => 'form-control',
                'required',
            ]) !!}
        </div>

        <div class="form-group">
            <label for="status">{{__('common.select_bank_name')}}</label>
            {!! Form::select('bank_id', $banks, null, ['id'=>'bank_id',
                'class' => 'form-control select2 ',
                'placeholder' =>trans('common.please_select'),
            ]) !!}
        </div>

        <div class="form-group">
            <label for="status">{{__('common.select_bank_branch')}} </label>
            {!! Form::select('bank_branch_id', $bank_branches, null, [
                'class' => 'form-control select2 ','id'=>'bank_branch_id',
                'placeholder' => trans('common.please_select'),
            ]) !!}
        </div>

        <div class="form-group">
            <label for="bank_account">{{__('common.bank_account')}}  </label>
            {!! Form::text('bank_account', null, [
                'class' => 'form-control ',
                'placeholder' =>trans('common.bank_account_number'),
                'autocomplete' => 'off',
            ]) !!}
        </div>

        <div class="form-group">
            <label for="status">{{__('common.status')}}   </label>
            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, [
                'id' => 'status',
                'class' => 'form-control',
                'required',
            ]) !!}
        </div>


        <div class="form-group">
            <label for="name">{{__('common.resume')}}/ {{__('common.cv_file')}} </label>

            <input name="submitted_files['cv']" type="file" accept="image/*, application/pdf" class='preview-file'
                   onchange="getImagePreview(this)"/>
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">

            @php
                $cv = isset($model) ? $model->getFile($model->id, "cv") : false ;
            @endphp
            @if($cv)

                @if($cv->attached && (\Illuminate\Support\Str::contains($cv->attached_file, ['.jpg','.png','.jpeg','gif'])) )
                    <img src="{{ $cv->attached_file }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                @else
                <a target="_blank" class="btn btn-primary btn-sm" href="{{ $cv->attached ? $cv->attached_file : 'javascript:void(0)' }}">
                    <i class="fa fa-files-o"></i> View file </a>
                @endif
            @endif
            </div>


        </div>

    </div>


    <div class="col-md-6 py-3">

        <div class="form-group">
            <label for="name">{{__('common.passport_number')}} </label>
            {!! Form::text('passport_number', null, [
                'id' => 'passport_number',
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_passport_number'),
            ]) !!}


            <br/>

            <input name="submitted_files['passport_number']" type="file" accept="image/*, application/pdf"
                   class='preview-file' onchange="getImagePreview(this)"/>
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">


            @php
                $passport_number = isset($model) ? $model->getFile($model->id, "passport_number") : false ;
            @endphp
            @if($passport_number)

                @if($passport_number->attached && (\Illuminate\Support\Str::contains($passport_number->attached_file, ['.jpg','.png','.jpeg','gif'])) )
                    <img src="{{ $passport_number->attached_file }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                @else
                <a target="_blank" class="btn btn-primary btn-sm"
                   href="{{ $passport_number->attached ? $passport_number->attached_file : 'javascript:void(0)' }}">
                    <i class="fa fa-files-o"></i>{{__('common.view_file')}}  </a>
                @endif
            @endif
            </div>
        </div>


        <div class="form-group">
            <label for="initial_balance">{{__('common.visa_expiry_date')}}  </label>
            {!! Form::text('visa_expire', isset($model) ? create_date_format($model->visa_expire, '/') : null, [
                'class' => 'form-control expire-date ',
                'placeholder' => 'visa expire Date ',
                'autocomplete' => 'off',
            ]) !!}


            <br/>
            <input name="submitted_files['visa_expire']" type="file" accept="image/*, application/pdf"
                   class='preview-file' onchange="getImagePreview(this)"/>
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">

            @php
                $visa = isset($model) ? $model->getFile($model->id, "visa_expire") : false ;
            @endphp
            @if($visa)
                @if($visa->attached && (\Illuminate\Support\Str::contains($visa->attached_file, ['.jpg','.png','.jpeg','gif'])) )
                    <img src="{{ $visa->attached_file }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                @else
                <a target="_blank" class="btn btn-primary btn-sm"
                   href="{{ $visa->attached ? $visa->attached_file : 'javascript:void(0)' }}">
                    <i class="fa fa-files-o"></i> View file </a>
            @endif
            @endif
            </div>


        </div>


        <div class="form-group">
            <label for="name">{{__('common.permanent_residence_pr_number')}} </label>
            {!! Form::text('pr_number', null, [
                'id' => 'pr_number',
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_pr_number'),
            ]) !!}

            <br/>

            <input name="submitted_files['pr_number']" type="file" accept="image/*, application/pdf"
                   class='preview-file' onchange="getImagePreview(this)"/>
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">


            @php
                $pr_number = isset($model) ? $model->getFile($model->id, "pr_number") : false ;
            @endphp
            @if($pr_number)
                    @if($pr_number->attached && (\Illuminate\Support\Str::contains($pr_number->attached_file, ['.jpg','.png','.jpeg','gif'])) )
                        <img src="{{ $pr_number->attached_file }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                    @else

                <a target="_blank" class="btn btn-primary btn-sm" href="{{ $pr_number->attached ? $pr_number->attached_file : 'javascript:void(0)' }}">
                    <i class="fa fa-files-o"></i> View file </a>
            @endif
            @endif
            </div>
        </div>
        <div class="form-group">
            <label for="initial_balance">{{__('common.permanent_residence_pr_expiry_date')}} </label>
            {!! Form::text('pr_expire', isset($model) ? create_date_format($model->pr_expire, '/') : null, [
                'class' => 'form-control expire-date ',
                'placeholder' =>trans('common.pr_expire_date'),
                'autocomplete' => 'off',
            ]) !!}
        </div>



        <div class="form-group">
            <label for="name">{{__('common.insurance_company')}} </label>
            {!! Form::text('insurance_company', null, [
                'id' => 'insurance_company',
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_insurance_company'),
            ]) !!}
        </div>

        <div class="form-group">
            <label for="name">{{__('common.insurance_number')}}  </label>
            {!! Form::text('insurance_number', null, [
                'id' => 'insurance_number',
                'class' => 'form-control',
                'placeholder' =>trans('common.enter_insurance_number') ,
            ]) !!}


            <br/>
            <input name="submitted_files['insurance_number']" type="file" accept="image/*, application/pdf"
                   class='preview-file' onchange="getImagePreview(this)"/>
            <input type="hidden" id="front-image-url" value="">
            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">

            @php
                $insurance_number = isset($model) ? $model->getFile($model->id, "insurance_number") : false ;
            @endphp
            @if($insurance_number)

                @if($insurance_number->attached && (\Illuminate\Support\Str::contains($insurance_number->attached_file, ['.jpg','.png','.jpeg','gif'])) )
                    <img src="{{ $insurance_number->attached_file }}" width="100" height="100" style="margin-top: 5px; width: 100px !important;">
                @else

                <a target="_blank" class="btn btn-primary btn-sm"
                   href="{{ $insurance_number->attached ? $insurance_number->attached_file : 'javascript:void(0)' }}">
                    <i class="fa fa-files-o"></i> {{__('common.view_file')}} </a>
            @endif
            @endif
            </div>
        </div>


    </div>

</div>


{{--<div class="col-md-12">--}}
{{--<div class="form-group">--}}
{{--<label class="col-md-12">Collected Files </label>--}}
{{--@foreach ($document_types as $key => $value)--}}
{{--<div class="col-md-4 mb-3">--}}
{{--{!! Form::checkbox('submitted_documents[]', $key, null, [--}}
{{--'class' => 'file-select',--}}
{{--isset($model) ? in_array($key, $submitted_documents) ? 'checked=checked' : '',--}}
{{--]) !!} {{ $value }}--}}
{{--</div>--}}
{{--@endforeach--}}
{{--</div>--}}
{{--</div>--}}

{{--<div class="col-md-12">--}}
{{--<hr/>--}}
{{--<div class="form-group">--}}
{{--@foreach ($document_types as $key => $value)--}}
{{--<div class="col-md-4 mb-3 hidden" id="submit_file_{{ $key }}">--}}
{{--<b><i>{{ $value }}</i></b> <br/>--}}
{{--<input name="submitted_files[{{ $key }}]" type="file" id="file_{{ $key }}"--}}
{{--class="hidden"/>--}}

{{--</div>--}}
{{--@endforeach--}}
{{--</div>--}}
{{--</div>--}}

{{--@if (isset($model) ? count($model->attached_file) > 0)--}}
{{--<div class="col-md-12">--}}
{{--<h4>Saved Files: </h4>--}}
{{--<hr/>--}}
{{--<div class="form-group">--}}
{{--@foreach ($model->attached_file as $value)--}}
{{--<div class="col-md-4 mb-3">--}}
{{--<b><i>{{ $value->document_type->name }}</i></b> <br/>--}}
{{--<a target="_blank" class="btn btn-primary btn-sm" href="{{ $value->attached ? $value->attached_file : 'javascript:void(0)' }}">--}}
{{--<i class="fa fa-files-o"></i> View file </a>--}}
{{--</div>--}}
{{--@endforeach--}}
{{--</div>--}}
{{--</div>--}}
{{--@endif--}}


{{-- <div class="col-md-12 mb-5"> --}}
{{-- <div class="form-group "> --}}
{{-- <label for="role">{{ isset($assignRole)?'Assigned ':'' }} Roles  </label><br/> --}}

{{-- @foreach ($roles as $key => $role) --}}
{{-- <div class="col-md-3"> --}}
{{-- @if (isset($assignRole)) --}}
{{-- {!! Form::checkbox('roles[]', $key, null, [ 'checked' => in_array($key, $assignRole )] ) !!} {{ $role }} --}}
{{-- @else --}}
{{-- {!! Form::checkbox('roles[]', $key, null ) !!} {{ $role }} --}}
{{-- @endif --}}
{{-- </div> --}}
{{-- @endforeach --}}
{{-- </div><br/> --}}
{{-- </div> --}}

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {


            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.expire-date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "todayHighlight": true,
                "autoclose": true
            });

            $('#dob').datepicker({
                "changeMonth": true,
                "maxDate": '0',
                "endDate": "+0d",
                "changeYear": true,
                "autoclose": true,
                'yearRange': '1971:{{ date('Y') }}',
            });


            $('.select2').select2();

            $("#bank_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('search.select_bank_branch') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    console.log(data);
                    if(data.status == "success")
                    {
                        var html = "<option value=''>Select Bank Branch </option>";

                        $.each( data.modals, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });

                        $('#bank_branch_id').html(html);
                    }else{
                        bootbox.alert("No data Found!! ");
                    }
                });
            });

            // $(".file-select").change(function () {
            //
            //     var fileID = $(this).val();
            //     if ($(this).is(':checked')) {
            //         $("#submit_file_" + fileID).removeClass("hidden");
            //         $("#file_" + fileID).removeClass("hidden");
            //     } else {
            //         $("#submit_file_" + fileID).addClass("hidden");
            //         $("#file_" + fileID).addClass("hidden");
            //     }
            // });


            // $("#designation_id").change(function () {
            //
            //     var salary = $("#designation_id :selected").data('salary');
            //     var commission = $("#designation_id :selected").data('commission');
            //     var commission_area = $("#designation_id :selected").data('area');
            //
            //     $("#salary").val(salary);
            //     $("#commission").val(commission);
            //
            //     $('.area_hidden').addClass('hidden');
            //
            //     if (commission_area == "division") {
            //         $('.division').removeClass('hidden');
            //     } else if (commission_area == "region") {
            //         $('.division').removeClass('hidden');
            //         $('.region').removeClass('hidden');
            //     } else if (commission_area == "area") {
            //         $('.division').removeClass('hidden');
            //         $('.region').removeClass('hidden');
            //         $('.district').removeClass('hidden');
            //         $('.thana').removeClass('hidden');
            //         $('.area').removeClass('hidden');
            //     } else if (commission_area == "thana") {
            //         $('.division').removeClass('hidden');
            //         $('.region').removeClass('hidden');
            //         $('.district').removeClass('hidden');
            //         $('.thana').removeClass('hidden');
            //     } else if (commission_area == "") {
            //         $('.area_hidden').removeClass('hidden');
            //     }
            //
            // });
        });


    </script>

    @include('common.area_script')
@endpush
