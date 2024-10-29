<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentDetail extends Model
{
    use LogsActivity;
    protected $fillable = ['transaction_details_id'];

    protected $guarded = [
        'number', 'date', 'pass_date', 'provide_date', 'issuer_name', 'email',
        'receiver_name', 'short_description'
    ];


    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
}
