<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeSubmittedDocument extends Model
{
    use LogsActivity;

    protected $fillable = ['employee_id', 'document_type_id', 'attached'];
    protected $appends = ["attached_file"];

    public $timestamps = false;

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class, 'id', "employee_id") ;
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class) ;
    }

    /**
     * Get the Sharer files.
     *
     * @return string
     */
    public function getAttachedFileAttribute()
    {
        return asset('storage/app/public/'. $this->attached);
    }

}
