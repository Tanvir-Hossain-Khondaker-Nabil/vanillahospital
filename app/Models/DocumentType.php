<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DocumentType extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function document_type()
    {
        return $this->belongsTo(SharerSubmittedDocument::class) ;
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
