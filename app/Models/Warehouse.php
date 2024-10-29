<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Warehouse extends Model
{

    protected $table = 'warehouses';

    protected $guarded = [];


    protected $appends = ['title_phone', 'title_address', 'featured_image_path', 'gallery_images_path'];

    public function getTitlePhoneAttribute()
    {
        return $this->title." (".$this->mobile.")";
    }

    public function getTitleAddressAttribute()
    {
        return $this->title.( $this->address != null ?? " (".$this->address.")");
    }


    public function scopeActive($query)
    {
        return $query->where('active_status', 1);
    }


    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getFeaturedImagePathAttribute()
    {
        return asset('storage/app/public/featured_image/'. $this->featured_image);
    }

    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getGalleryImagesPathAttribute()
    {
        $gallery_images = [];

        if($this->gallery_images != '')
        {
            $images = explode(',', $this->gallery_images);

            foreach ($images as $key => $image)
            {
                $gallery_images[$key] = asset('storage/app/public/gallery_images/'. trim($image));
            }
        }

        return $gallery_images;
    }

    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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


    /**
     * Get the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');

    }

    /**
     * Get the Division.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);

    }

    /**
     *
     * Get the upazilla.
     */
    public function upazilla()
    {
        return $this->belongsTo(Upazilla::class);

    }

    /**
     * Get the union.
     */
    public function union()
    {
        return $this->belongsTo(Union::class);

    }

    /**
     * Get the District.
     */
    public function district()
    {
        return $this->belongsTo(District::class);

    }

    /**
     * Get the District.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);

    }

    /**
     * Get the Region.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);

    }

    /**
     * Get the Thana.
     */
    public function thana()
    {
        return $this->belongsTo(Thana::class);

    }


    public function warehouseStock($item_id, $warehouse_id)
    {
        $stock = WarehouseStock::where('warehouse_id', $warehouse_id)
            ->where('item_id', $item_id)
            ->first();

        return $stock ? $stock->stock : 0;
    }

}
