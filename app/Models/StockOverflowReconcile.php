<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class StockOverflowReconcile extends Model
{
    use  LogsActivity;

    protected $table = 'stock_overflow_reconcile';

    protected $fillable = ['item_id', 'qty', 'enter_date', 'fiscal_year_id', 'closing_date'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function fiscal_year()
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id','id');
    }


}
