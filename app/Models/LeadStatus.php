<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{

    protected $fillable = [
        'lead_id', 'lead_status', 'company_id','comment', 'created_by',
    ];


    /**
     * Get the user that owns the LeadStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}