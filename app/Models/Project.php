<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project', 'address', 'image', 'expire_date', 'description', 'project_category_id', 'long', 'lat', 'status', 'created_by', 'updated_by', 'client_id', 'project_status', 'progress_status', 'start_date', 'price', 'company_id', 'complete_status', 'broker_id', 'country_id','division_id','district_id','area_id','lead_id','commission', 'working_days',
    ];

    protected $guarded = [];
    
    protected $appends = [
        'project_image_path',
    ];

    static public function get_progress_status()
    {
        $data = [
            'open' => 'Open',
            'completed' => 'Completed',
            'hold' => 'Hold',
            'canceled' => 'Canceled'
        ];


        return $data;
    }

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function projectCategory()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id', 'id');
    }

    public function employee_project()
    {
        return $this->hasOne(EmployeeProject::class, 'project_id', 'id')->where('status', 'active')->select('employee_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }


    public function task()
    {
        return $this->HasMany(Task::class, 'project_id', 'id')
            ->with(['employee','taskHistory']);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function getProjectImagePathAttribute()
    {
        return asset('storage/app/public/project_image/' . $this->image);
    }

    public function labeling()
    {

        return $this->hasMany(Labeling::class, 'modal_id', 'id')
            ->where('modal', 'Project')->with('label');
    }
}
