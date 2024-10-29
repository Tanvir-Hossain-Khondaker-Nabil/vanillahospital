<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaStore extends Model
{
    protected $table = 'media_files';

    protected $fillable = ['use_model','file_type','file_name','extension','model_id','file_store_path'];

}
