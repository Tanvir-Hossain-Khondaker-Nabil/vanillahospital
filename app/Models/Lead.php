<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
       'lead_code', 'title', 'lead_category_id', 'lead_company_id', 'client_id', 'company_id', 'created_by',
        'updated_by'
    ];

    protected $appends = ['code_title'];

    public function getCodeTitleAttribute()
    {
        return $this->lead_code." - ".$this->title;
    }


    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function client()
    {

        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function leadCategory()
    {

        return $this->belongsTo(LeadCategory::class, 'lead_category_id', 'id');
    }

    public function clientCompany()
    {

        return $this->belongsTo(ClientCompany::class, 'lead_company_id', 'id');
    }

    public function labeling()
    {

        return $this->hasMany(Labeling::class, 'modal_id', 'id')->where('modal', 'Lead')->with('label');
        // return $this->hasMany(Labeling::class, 'modal_id', 'id');
    }

    public function leadStatus()
    {
        return $this->hasMany(LeadStatus::class, 'lead_id', 'id')->with('user');
    }

    static public function get_statuses()
    {
        $data = [
            'New' => 'New',
            'Qualified' => 'Qualified',
            'Discussion' => 'Discussion',
            'Negotiation' => 'Negotiation',
            'Won' => 'Won',
            'Lost' => 'Lost',
            'Canceled' => 'Canceled',
        ];

        return $data;
    }


}
