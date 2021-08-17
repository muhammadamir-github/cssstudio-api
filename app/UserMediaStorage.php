<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserMediaStorage extends Model
{
    protected $table = 'user_media_storage';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'storage_quota', 'free_space', 'folder_name', 'created_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}

