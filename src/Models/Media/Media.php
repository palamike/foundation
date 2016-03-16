<?php

namespace Palamike\Foundation\Models\Media;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

    protected $table = "medias";

    protected $fillable = [
        'category','file_name','extension','mime',
        'path','web_path','thumb_path','meta_data',
        'created_by','updated_by','created_by_name','updated_by_name'
    ];

    protected $casts = [
        'meta_data' => 'array'
    ];
}
