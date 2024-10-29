<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProject extends Model
{
    protected $fillable = [
        'project_id', 'employee_id', 'status'
    ];

    public $timestamps = false;


    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class, 'employee_id', 'id');
    }

}
