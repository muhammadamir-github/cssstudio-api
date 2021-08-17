<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ElementStyle;

class ElementStyleCss extends Model
{
	protected $table = "element_styles_css";

    public $fillable = ["id", "style_id", "type" , "for_element", "css_changes", "created_at"];
    public $hidden = ["id", "created_at", "type" , "style_id"];
    public $timestamps = false;

}
