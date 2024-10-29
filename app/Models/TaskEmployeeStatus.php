<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskEmployeeStatus extends Model
{
    protected $fillable =[
     'task_id','employee_info_id','updated_by','status','comments',
    ];

    public function task()
    {

        return $this->belongsTo(Task::class, 'task_id', 'id')->with('project');
    }

    public function employee()
    {

        return $this->belongsTo(EmployeeInfo::class, 'employee_info_id', 'id')->with('designation');
    }
}
