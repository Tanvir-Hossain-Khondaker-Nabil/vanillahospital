<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Labeling extends Model
{
    protected $fillable =[
        'modal_id','modal','company_id','label_id','created_by','updated_by',
      ];

      public function label(){

        return $this->belongsTo(Label::class, 'label_id', 'id');
     }
}