<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'customers';

    // Must be Assignable
    protected $fillable = [
        'name', 'phone', 'status', 'address', 'email', 'division_id',
        'district_id', 'area_id', 'upazilla_id', 'union_id', 'company_id', 'member_id'
    ];


    protected $appends = ['name_phone', 'name_address'];


    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function getNamePhoneAttribute()
    {
        return $this->name." (".$this->phone.")";
    }

    public function getNameAddressAttribute()
    {
        return $this->name.( $this->address != null ?? " (".$this->address.")");
    }

    public function getCustomerDetailsAttribute()
    {
        return $this->name." (".$this->phone.")".( $this->address != null ?? " (".$this->address.")");
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

    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }

    /**
     * Get the Created by User.
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the Updated by User.
     */
    public function updated_by()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);

    }

    /**
     * Get the member.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);

    }

    /**
     * Get the Division.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);

    }
    /**
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
     * Relation with Sales
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function sales()
    {
        return $this->hasMany( Sale::Class,'customer_id' );
    }


}
