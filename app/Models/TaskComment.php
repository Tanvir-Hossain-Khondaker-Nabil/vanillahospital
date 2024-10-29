<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable =[
        'task_id', 'employee_info_id', 'comments'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class, 'employee_info_id', 'id');
    }
}