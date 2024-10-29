<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitBalanceTrack extends Model
{

    protected $guarded = [];

    public function fiscal_year()
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }

}
