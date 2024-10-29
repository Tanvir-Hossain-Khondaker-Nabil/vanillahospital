<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierPurchases extends Model
{
    use LogsActivity;

    protected $table = 'supplier_purchases';

    protected $fillable = [ 'item_id', 'qty', 'member_id', 'company_id', 'supplier_id' ];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
    }


    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class,'member_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }


    public function supplier()
    {
        return $this->belongsTo(SupplierOrCustomer::class, 'supplier_id');
    }
}
