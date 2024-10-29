<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $fillable = ['name', 'display_text', 'description', 'price',  'status', 'time_period', 'total_users'];

    protected $guarded = ['discount','discount_type', 'created_by', 'updated_by'];

    protected $appends = ['packages'];

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
     * Get the Packages Details.
     *
     * @return string
     */
    public function getPackagesAttribute()
    {
        return $this->display_text. ', Price( ' .$this->price.'tk ), Time Period-'.($this->time_period == 0 ? 'Unlimited' : $this->time_period.' Months');
    }
}
