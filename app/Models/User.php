<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;


class User extends Authenticatable
{

    use LogsActivity, Notifiable;
    use EntrustUserTrait { restore as private restoreA; }
    use SoftDeletes { restore as private restoreB; }

    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'member_id', 'membership_id', 'verify_token', 'status','company_id',
        'phone', 'password', 'support_pin'
    ];

//    protected $guarded = [ 'password' ];

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token', 'verify_token', 'support_pin', 'help_pin', 'api_token'
    ];

    protected $appends = ['user_details', 'uc_full_name', 'help_pin', 'user_phone'];

    /**
     * Get the members.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the branches.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the membership.
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function roles()
    {
        // return $this->belongsToMany(Role::class);
        return $this->belongsToMany(Role::class);
    }

    public function scopeMembersUser($query)
    {

        if(!Auth::user()->can(['super-admin']))
            return $query->where('member_id', Auth::user()->member_id)
                     ->where('membership_id', Auth::user()->membership_id);
    }

    public function findRoleUser($n)
    {
        $roleUser = DB::table('role_user')
                        ->whereIn('role_id',Role::whereIn('name', $n)->pluck('id')->toArray())
                        ->pluck('user_id')
                        ->toArray();

        return $roleUser;

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

    public function getUserDetailsAttribute()
    {
        return $this->full_name." (".$this->email." ".$this->phone.") ";
    }

    public function getUcFullNameAttribute()
    {
        return ucfirst($this->full_name);
    }

    public function getUserPhoneAttribute()
    {
        return ucfirst($this->full_name).( $this->phone ? " (".$this->phone.") " : '');
    }

    public function setSupportPinAttribute($value)
    {
        $this->attributes['support_pin'] = Crypt::encryptString($value);

    }

    public function getHelpPinAttribute()
    {
        $decrypted = $this->support_pin != null ? Crypt::decryptString($this->support_pin) : "";

        return $decrypted;
    }

    public function scopeSystemUser($query)
    {
        return $query->whereNotIn('full_name', ['superadmin','developer','master-member']);
    }


    /**
     * Scope a query to only Member
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
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


    public function sharer()
    {
        return $this->hasOne(SupplierOrCustomer::class, '');
    }

    public function employee()
    {
        return $this->hasOne(EmployeeInfo::class, 'user_id')->with(['designation','division','district','area','country']);
    }


    public function getProfilePhotoAttribute()
    {
        return asset('storage/app/public/photo/'. $this->photo);
    }

}
