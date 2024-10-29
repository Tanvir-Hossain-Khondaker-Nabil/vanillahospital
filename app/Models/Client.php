<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'label', 'phone_1', 'phone_2', 'division_id', 'district_id', 'area_id', 'address_1', 'address_2', 'status', 'owner_status', 'facebook', 'instagram', 'linkedin', 'client_image', 'card_image', 'quotationer_id', 'company_id', 'created_by', 'updated_by','country_id',
    ];

    protected $appends = [
        'client_image_path', 'card_image_path',
    ];

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function company()
    {
        return $this->belongsTo(QuotationCompany::class, 'quotationer_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }


    /**
     * Get the Client image path.
     *
     * @return string
     */
    public function getClientImagePathAttribute()
    {
        return asset('storage/app/public/client_image/' . $this->client_image);
    }

    /**
     * Get the Card image path.
     *
     * @return string
     */
    public function getCardImagePathAttribute()
    {
        return asset('storage/app/public/card_image/' . $this->card_image);
    }
}