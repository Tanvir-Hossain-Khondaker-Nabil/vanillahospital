<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesRequisitionDetail extends Model
{
    use LogsActivity;
    protected $guarded = [];
    protected $appends = ['created_date'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function requisitions()
    {
        return $this->hasOne(SalesRequisition::class, 'id', 'sales_requisition_id');
    }

    public function item()
    {
        return $this->hasOne( Item::class, 'id', 'item_id');
    }

    public function dealer_stock($dealer_id, $item_id)
    {

        $item = DealerStock::where('dealer_id',$dealer_id)->where('item_id', $item_id)->first();

        return $item ? $item->stock : 0;

    }

    public function getCreatedDateAttribute()
    {
        return db_date_month_year_format($this->created_at);
    }

}
