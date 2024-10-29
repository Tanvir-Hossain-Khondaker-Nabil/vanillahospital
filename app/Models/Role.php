<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use LogsActivity;
    protected $fillable = ['name', 'display_name', 'status'];

    protected $guarded = ['description'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
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

    public function scopeFilter($query)
    {
        if(Auth::user()->hasRole(['super-admin']))
        {
        }else{
            $query = $query->where('name','!=', 'super-admin')
                ->where('name','!=', 'developer')
                ->where('name','!=', 'master-member');
        }

       return $query;
    }
}
