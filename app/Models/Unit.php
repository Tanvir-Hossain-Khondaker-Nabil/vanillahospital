<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Unit extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name', 'slug', 'display_name'
    ];
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
}
