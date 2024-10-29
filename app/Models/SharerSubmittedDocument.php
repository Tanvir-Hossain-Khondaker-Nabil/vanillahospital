<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SharerSubmittedDocument extends Model
{
    use LogsActivity;

    protected $fillable = ['sharer_id', 'document_type_id'];

    public $timestamps = false;

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function sharer()
    {
        return $this->belongsTo(SupplierOrCustomer::class) ;
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class) ;
    }
}
