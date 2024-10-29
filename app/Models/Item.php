<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{

    use LogsActivity, SoftDeletes;

    protected $fillable = [
      'item_name', 'unit', 'product_image', 'category_id', 'description', 'status', 'price',
      'guarantee', 'warranty', 'trade_price', 'pack_qty', 'free_qty',
      'skuCode', 'productCode', 'brand_id', 'is_service', 'purchase_price', 'main_product_id','is_repair_item'
    ];

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

    public function scopeIsRepair($query)
    {
        return $query->where('is_repair_item', 1);
    }

    public function scopeIsService($query)
    {
        return $query->where('is_service', 1);
    }

    public function scopeIsNotService($query)
    {
        return $query->where('is_service', 0);
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id','category_id')->withDefault(['display_name'=>'']);
    }

    protected $appends = ['product_image_path', 'item_details'];

    public function getItemDetailsAttribute()
    {
        return $this->id."- ".$this->item_name;
    }

    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getProductImagePathAttribute()
    {
        return asset('storage/app/public/product_image/'. $this->product_image);
    }

    public function stock_details(){

        return $this->hasOne(Stock::class);
    }

    public function stock_report(){

        return $this->hasMany(StockReport::class);
    }

    public function sale(){

        return $this->hasMany(SaleDetails::class);
    }

    public function purchase(){

        return $this->hasMany(PurchaseDetail::class);
    }

    public function latest_purchase(){

        return $this->hasOne(PurchaseDetail::class)->latest();
    }

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('item_name', 'DESC');
    }


    public function scopeOrderAsc($query)
    {
        return $query->orderBy('item_name', 'ASC');
    }


    /**
     * Scope a query to only Member
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
    }

    /**
     * Scope a query to only Company
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }

    public function brand(){

        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function main_product(){

        return $this->belongsTo(MainProduct::class, 'main_product_id');
    }
    public function variants(){

        return $this->hasMany(ItemVariant::class, 'item_id');
    }

    public function initial_warehouses(){

        return $this->hasMany(WarehouseHistory::class, 'item_id')->where('model', 'initial');
    }

    public function warehouse_stocks(){

        return $this->hasMany(WarehouseStock::class, 'item_id');
    }

    public function item_details(){

        return $this->hasMany(ItemDetail::class, 'item_id', 'id');
    }

    public function purchase_requisition_details(){

        return $this->hasMany(RequisitionDetail::class, 'item_id', 'id');
    }

    public function purchase_details(){

        return $this->hasMany(PurchaseDetail::class, 'item_id', 'id');
    }

    public function sale_requisition_details(){

        return $this->hasMany(SalesRequisitionDetail::class, 'item_id', 'id');
    }

    public function sale_details(){

        return $this->hasMany(SaleDetails::class, 'item_id', 'id');
    }

    static public function multiUnitCheck()
    {
        return Item::select('unit')->groupBy('unit')->count() >1 ? true : false;
    }
}
