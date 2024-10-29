<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_code', 'api_access_key', 'membership_id','country_id', 'expire_date', 'status'
    ];

    protected $guarded = ['created_by', 'updated_by'];
    /**
     * Get the membership.
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Get the country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
