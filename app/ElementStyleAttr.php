<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElementStyleAttr extends Model
{
    protected $table = "element_styles_data_attributes";

    public $fillable = ["id", "style_id", "type", "for_element", "attributes", "created_at"];
    public $hidden = ["id", "created_at", "type" , "style_id"];
    public $timestamps = false;
}
