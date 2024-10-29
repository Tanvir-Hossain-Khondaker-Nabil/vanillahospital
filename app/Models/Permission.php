<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use LogsActivity;
    protected $fillable = ['name', 'display_name', 'group_name'];

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
}
