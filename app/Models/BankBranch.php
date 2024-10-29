<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class BankBranch extends Model
{
    use LogsActivity;

    protected $guarded  = [];
    protected static $logAttributes = ['branch_name', 'status', 'updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];

  

}
