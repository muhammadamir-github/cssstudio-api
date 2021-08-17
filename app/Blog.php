<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'blogs';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'heading', 'content', 'image_link','author','created_at'];

}
