<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVideoThumbnail extends Model
{
    protected $table = 'user_video_thumbnails';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'video_id', 'file_name', 'path', 'extension','size','created_at'];

    //protected $hidden = ['user_id', 'file_name', 'extension' , 'created_at'];

}
