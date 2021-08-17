<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserMedia extends Model
{
    protected $table = 'user_media';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'type', 'file_name', 'name', 'title', 'description', 'path', 'extension','size','created_at','encrypt_str'];

    protected $hidden = ['user_id', 'file_name', 'extension' , 'created_at' , 'encrypt_str'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
