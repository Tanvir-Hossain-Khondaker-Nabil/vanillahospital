<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentMethod extends Model
{
    use LogsActivity;
    protected $table = 'payment_methods';

    protected $guarded = [];

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
