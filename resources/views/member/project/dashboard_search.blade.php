{!! Form::open(['route' => Route::current()->getName(),'method' => 'get', 'role'=>'form' ]) !!}

<div class="box-body">

    <div class="row">
        <div class="col-md-5">
            <label>Project Name</label>
            {!! Form::select('project_id', $projects, null,['id'=>'project_id','class'=>'form-control select2','placeholder'=>'Select Project']); !!}
        </div>
        <div class="col-md-2 pl-0  text-center">
            <label> Date </label>
            <input class="form-control date" name="date" value="" autocomplete="off"/>
        </div>
        <div class="col-md-2 pl-0 text-center">
            <label> Project Status </label>
            <input class="form-control bg-white" disabled name="from_date" value="" autocomplete="off"/>
        </div>
        <div class="col-md-2  pl-0 text-center">
            <label> % Complete </label>
            <input class="form-control bg-white" disabled name="from_date" value="" autocomplete="off"/>
        </div>
        <div class="col-md-1 margin-top-23 px-0">
            <label></label>
            <button type="submit" class="btn btn-primary"> <i class="fa fa-search"> </i></button>
        </div>
    </div>
</div>
{!! Form::close() !!}
