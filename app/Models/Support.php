<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $table = 'staff_supports';

    protected $fillable = [
        'title', 'message', 'status', 'message_status', 're_id', 'generated_id', 'company_id',
        'reply_status'
    ];


    public function scopeAuthCompany($query)
    {

        if(!\Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function sender()
    {
        return $this->belongsTo(User::class,'generated_id', 'id')->withTrashed();
    }


}
