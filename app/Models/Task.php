<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'image', 'description', 'start_date', 'end_date', 'priority', 'status', 'project_id', 'employee_info_id', 'company_id', 'created_by', 'updated_by'
    ];

    protected $appends = [
        'task_image_path',
    ];

    static public function get_statuses($value = "")
    {
        $data = [
            'to_do' => 'Not Started',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done'
        ];

        return $value != "" ? $data[$value] : $data;
    }

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function project()
    {

        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function employee()
    {

        return $this->belongsTo(EmployeeInfo::class, 'employee_info_id', 'id')->with(['designation']);
    }

    public function taskEmployeeStatus()
    {

        return $this->hasMany(TaskEmployeeStatus::class, 'task_id', 'id');
    }

    public function taskHistory()
    {

        return $this->hasMany(TaskStatusHistory::class, 'task_id', 'id');
    }

    public function labeling()
    {

        return $this->hasMany(Labeling::class, 'modal_id', 'id')->where('modal', 'Task')->with('label');
    }

    public function label()
    {

        return $this->belongsTo(Label::class, 'label_id', 'id');
    }

    public function getTaskImagePathAttribute()
    {
        return asset('storage/app/public/task_image/' . $this->image);
    }
}
