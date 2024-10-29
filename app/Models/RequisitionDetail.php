<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RequisitionDetail extends Model
{
    use LogsActivity;
    protected $guarded = [];
    protected $appends = ['created_date'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function requisitions()
    {
        return $this->hasOne(Requisition::class, 'id', 'requisition_id');
    }

    public function item()
    {
        return $this->hasOne( Item::class, 'id', 'item_id');
    }

    public function getCreatedDateAttribute()
    {
        return db_date_month_year_format($this->created_at);
    }

}
