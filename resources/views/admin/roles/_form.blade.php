<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

$last_group = "";
?>

<div class="col-md-6">
    <div class="form-group">
        <label for="name">Name <span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>'Enter Name', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="display_name">Display Name <span class="text-red"> * </span> </label>
        {!! Form::text('display_name',null,['id'=>'display_name','class'=>'form-control','placeholder'=>'Enter Display Name', 'required']); !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="description">Description </label>
        {!! Form::textarea('description',null,['class'=>'form-control','rows'=>'6', 'placeholder'=>'Enter description']); !!}
    </div>
</div>

<div class="col-md-12 mb-3">
    <hr>
    <h4>Permissions</h4>
    <input type="checkbox" id="all_permissions"
           value="all" {{ count($permissions) == count($rolePermissions) ? "checked" : ''}}><b><i> All Permissions </i></b>
    <hr>


    @foreach($permissions as $key => $permission)

        @if($permission->group_name != $last_group)
            <div class="col-md-12 ">


                <button type="submit" class="btn btn-primary my-3">Update</button>

                <h5 class="font-weight-bold text-danger bg-gray p-3">

                    <input type="checkbox" data-target="pr_{{$permission->group_name}}" class="group-check" value="all">
                    {{ ucfirst(normal_writing_format($permission->group_name)) }}</h5>
            </div>
        @endif
        <div class="col-md-4 mt-3">
            <label>
                @if(isset($rolePermissions))
                    <input type="checkbox" class="pr_{{$permission->group_name}}" value="{{ $permission->id }}"
                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} name="permissions[]"> {{ $permission->display_name }}
                @else
                    <input type="checkbox" class="pr_{{$permission->group_name}}" value="{{ $permission->id }}"
                           name="permissions[]"> {{ $permission->display_name }}
                @endif
            </label><br/>
            <span class="ml-4 font-italic"> {{ $permission->name }}</span>
        </div>
        @php
            $last_group = $permission->group_name;
        @endphp
    @endforeach


</div>


@push('scripts')


    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.

            $("#all_permissions").click(function () {
                if ($(this).is(":checked")) {
                    $('input:checkbox').not(this).prop('checked', this.checked);
                } else {
                    $('input:checkbox').not(this).prop('checked', false);
                }
            });

            $(".group-check").click(function () {

                var target = $(this).data("target");

                if ($(this).is(":checked")) {
                    $('.' + target).prop('checked', this.checked);
                } else {
                    $('.' + target).prop('checked', false);
                }
            });
        })
    </script>
@endpush


