<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class FiscalYear extends Model
{
    use LogsActivity;

	protected $fillable = [
		'start_date', 'end_date', 'title', 'member_id', 'status', 'company_id'
	];

    protected static $logAttributes = ['title', 'start_date', 'end_date', 'member_id', 'status', 'company_id', 'created_by', 'updated_by'];
    protected static $recordEvents = ['deleted', 'updated'];

    protected $appends=['fiscal_year','start_date_display_format','end_date_display_format', 'fiscal_year_details'];


    public function getStartDateDisplayFormatAttribute()
    {
        return month_date_year_format($this->start_date);
    }

    public function getEndDateDisplayFormatAttribute()
    {
        return month_date_year_format($this->end_date);
    }

    public function getFiscalYearAttribute()
    {
        return str_replace("-","/", $this->start_date_display_format). " - " .str_replace("-","/",$this->end_date_display_format);
    }

    public function getFiscalYearDetailsAttribute()
    {
        $title= $this->title;
        $year = formatted_date_string($this->start_date)." - ".formatted_date_string($this->end_date);
        return $title." (".$year.")";
    }


    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get the country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Scope a query to only include active models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include Customer models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
    }

    /**
     * Scope a query to only include Customer models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id)->orWhereNull('company_id');

        return $query;
    }

}


