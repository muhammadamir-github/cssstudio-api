<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ElementStyleCss;

class ElementStyle extends Model
{
    protected $table = "element_styles";

    public $fillable = ["id", "type", "category" , "total_usage" , "created_at"];
    public $hidden = ["id", "created_at", "total_usage"];
    public $timestamps = false;

}
