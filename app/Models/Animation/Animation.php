<?php

namespace App\Models\Animation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animation extends Model
{
    use HasFactory;

    protected $table = 'animations';
    public $timestamps = false;

    protected $fillable = ["id", "user_id", "name", "css"];
    protected $hidden = ["isDeleted", "created_at", "updated_at", "deleted_at"];
}
