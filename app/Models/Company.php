<?php

namespace App\Models;

use App\Models\CabinClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Company extends Model
{

    use LogsActivity;

    protected $fillable = [
		'company_name', 'fiscal_year_id', 'logo', 'phone', 'address',
        'email', 'member_id', 'country_id', 'city', 'status', 'report_head_image',
        'report_head_sub_text', 'created_by', 'pad_header_image', 'pad_footer_image',
        'pad_footer_height', 'pad_header_height', 'pad_watermark_image', 'quote_ref', 'tax','show_room_status'
	];

	protected $appends = [
	    'company_logo_path', 'report_head_image_path',
        'pad_header_image_path', 'pad_footer_image_path',
        'pad_watermark_image_path'
    ];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

	public function fiscal_year()
	{
		return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
	}

	public function member()
	{
		return $this->belongsTo(Member::class, 'member_id');
	}
	public function cabinClass()
	{
		return $this->hasMany(CabinClass::class);
	}

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function weekend()
    {
        return $this->hasMany(Weekend::class)
                                    ->whereNull('month')
                                    ->whereNull('employee_id');

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
     * Get the Company Logo path.
     *
     * @return string
     */
    public function getCompanyLogoPathAttribute()
    {
        return asset('storage/app/public/company_logo/'. $this->logo);
    }
    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getReportHeadImagePathAttribute()
    {
        return asset('storage/app/public/company_report_head/'. $this->report_head_image);
    }
    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getPadHeaderImagePathAttribute()
    {
        return asset('storage/app/public/company_report_head/'. $this->pad_header_image);
    }
    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getPadFooterImagePathAttribute()
    {
        return asset('storage/app/public/company_report_head/'. $this->pad_footer_image);
    }
    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getPadWatermarkImagePathAttribute()
    {
        return asset('storage/app/public/company_report_head/'. $this->pad_watermark_image);
    }

    /**
     * Get the country.
     */
    public function country()
    {
    	return $this->belongsTo(Country::class);
    }

    public function scopeAuthMember($query)
    {
    	return $query->where('member_id', Auth::user()->member_id);
    }
}
