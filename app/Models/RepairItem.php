<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairItem extends Model
{
    protected $guarded = [];


    public function repair_order()
    {
        return $this->hasOne(RepairOrder::class, 'id', 'repair_id')->orderBy('date');
    }

    public function item()
    {
        return $this->hasOne( Item::class, 'id', 'item_id')->withTrashed();
    }

    public function service()
    {
        return $this->hasOne( Serviceing::class, 'id', 'item_id');
    }

}
