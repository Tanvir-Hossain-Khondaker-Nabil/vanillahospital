<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Store extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    protected $appends = ['name_phone', 'name_address', 'store_image_path'];

    public function getNamePhoneAttribute()
    {
        return $this->store_name." (".$this->mobile_no.")";
    }

    public function getNameAddressAttribute()
    {
        return $this->name.( $this->address != null ?? " (".$this->address.")");
    }


    public function scopeActive($query)
    {
        return $query->where('active_status', 1);
    }


    public function scopeApproved($query)
    {
        return $query->where('approval_status', 1);
    }

    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getStoreImagePathAttribute()
    {
        return asset('storage/app/public/store_image/'. $this->store_image);
    }

    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the branches.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    /**
     * Scope a query to only Company
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }


    /**
     * Scope a query to only Company
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthArea($query)
    {
        if(Auth::user()->hasRole('sales_man')) {
            $area_id = Auth::user()->area_id;
//            $query = $query->where('area_id', $area_id);

        }

        return $query;
    }

    /**
     * Get the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');

    }

    /**
     * Get the Designation.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);

    }

    /**
     * Get the Division.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);

    }

    /**
     *
     * Get the upazilla.
     */
    public function upazilla()
    {
        return $this->belongsTo(Upazilla::class);

    }

    /**
     * Get the union.
     */
    public function union()
    {
        return $this->belongsTo(Union::class);

    }

    /**
     * Get the District.
     */
    public function district()
    {
        return $this->belongsTo(District::class);

    }

    /**
     * Get the District.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);

    }

    /**
     * Get the Region.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);

    }

    /**
     * Get the Thana.
     */
    public function thana()
    {
        return $this->belongsTo(Thana::class);

    }
}
