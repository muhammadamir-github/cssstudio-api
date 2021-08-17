<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ElementStyle;
use App\ElementStyleCss;
use App\ElementStyleAttr;

class ElementStyleController extends Controller
{
    public function all($elementType)
    {
        $styles = ElementStyle::where("type",$elementType)->get();
        
        foreach($styles as $style){
            $style["css"] = ElementStyleCss::where("style_id",$style->id)->get();
            $style["attr"] = ElementStyleAttr::where("style_id",$style->id)->get();
        }

        return response()->json($styles);
    }

    public function addCheckBoxStyles(){
    	/*$colors = ["data-bg-o:#FF6F61","data-bg-o:#6B5B95","data-bg-o:#9B1B30","data-bg-o:#77212E","data-bg-o:#F5D6C6","data-bg-o:#FA9A85","data-bg-o:#5A3E36","data-bg-o:#CE5B78","data-bg-o:#935529","data-bg-o:#E08119","data-bg-o:#2A4B7C","data-bg-o:#577284","data-bg-o:#F96714","data-bg-o:#264E36","data-bg-o:#F3E0BE","data-bg-o:#2A293E","data-bg-o:#9F9C99","data-bg-o:#797B3A","data-bg-o:#DD4132","data-bg-o:#9E1030","data-bg-o:#FE840E","data-bg-o:#C62168","data-bg-o:#8D9440","data-bg-o:#FFD662","data-bg-o:#00539C","data-bg-o:#755139","data-bg-o:#D69C2F","data-bg-o:#616247","data-bg-o:#E8B5CE","data-bg-o:#D2C29D","data-bg-o:#343148","data-bg-o:#F0EAD6","data-bg-o:#615550","data-bg-o:#7F4145","data-bg-o:#BD3D3A","data-bg-o:#3F69AA","data-bg-o:#D5AE41","data-bg-o:#766F57","data-bg-o:#E47A2E","data-bg-o:#BE9EC9","data-bg-o:#F1EA7F","data-bg-o:#006E6D","data-bg-o:#485167","data-bg-o:#EAE6DA","data-bg-o:#D1B894","data-bg-o:#BCBCBE","data-bg-o:#A9754F","data-bg-o:#ECDB54","data-bg-o:#E94B3C","data-bg-o:#6F9FD8","data-bg-o:#944743","data-bg-o:#DBB1CD","data-bg-o:#EC9787","data-bg-o:#00A591","data-bg-o:#6C4F3D","data-bg-o:#EADEDB","data-bg-o:#BC70A4","data-bg-o:#BFD641","data-bg-o:#2E4A62","data-bg-o:#B4B7BA","data-bg-o:#C0AB8E","data-bg-o:#F0EDE5","data-bg-o:#92B558","data-bg-o:#DC4C46","data-bg-o:#672E3B","data-bg-o:#F3D6E4","data-bg-o:#C48F65","data-bg-o:#223A5E","data-bg-o:#898E8C","data-bg-o:#005960","data-bg-o:#9C9A40","data-bg-o:#4F84C4","data-bg-o:#D2691E","data-bg-o:#578CA9","data-bg-o:#F6D155","data-bg-o:#004B8D","data-bg-o:#F2552C","data-bg-o:#95DEE3","data-bg-o:#EDCDC2","data-bg-o:#CE3175","data-bg-o:#5A7247","data-bg-o:#CFB095","data-bg-o:#4C6A92","data-bg-o:#92B6D5","data-bg-o:#838487","data-bg-o:#B93A32","data-bg-o:#AF9483","data-bg-o:#AD5D5D","data-bg-o:#006E51","data-bg-o:#D8AE47","data-bg-o:#9E4624","data-bg-o:#B76BA3","data-bg-o:#F7CAC9","data-bg-o:#F7786B","data-bg-o:#91A8D0","data-bg-o:#034F84","data-bg-o:#98DDDE","data-bg-o:#9896A4","data-bg-o:#B18F6A","data-bg-o:#FAE03C","data-bg-o:#79C753","data-bg-o:#88B04B","data-bg-o:#955251","data-bg-o:#B565A7","data-bg-o:#009B77","data-bg-o:#DD4124","data-bg-o:#D65076","data-bg-o:#45B8AC","data-bg-o:#EFC050","data-bg-o:#5B5EA6","data-bg-o:#9B2335","data-bg-o:#DFCFBE","data-bg-o:#55B4B0","data-bg-o:#E15D44","data-bg-o:#7FCDCD","data-bg-o:#BC243C","data-bg-o:#C3447A","data-bg-o:#98B4D4"];*/

        //$checkboxesColors = ["data-bg:#FF6F61","data-bg:#6B5B95","data-bg:#9B1B30","data-bg:#77212E","data-bg:#F5D6C6","data-bg:#FA9A85","data-bg:#5A3E36","data-bg:#CE5B78","data-bg:#935529","data-bg:#E08119","data-bg:#2A4B7C","data-bg:#577284","data-bg:#F96714","data-bg:#264E36","data-bg:#F3E0BE","data-bg:#2A293E","data-bg:#9F9C99","data-bg:#797B3A","data-bg:#DD4132","data-bg:#9E1030","data-bg:#FE840E","data-bg:#C62168","data-bg:#8D9440","data-bg:#FFD662","data-bg:#00539C","data-bg:#755139","data-bg:#D69C2F","data-bg:#616247","data-bg:#E8B5CE","data-bg:#D2C29D","data-bg:#343148","data-bg:#F0EAD6","data-bg:#615550","data-bg:#7F4145","data-bg:#BD3D3A","data-bg:#3F69AA","data-bg:#D5AE41","data-bg:#766F57","data-bg:#E47A2E","data-bg:#BE9EC9","data-bg:#F1EA7F","data-bg:#006E6D","data-bg:#485167","data-bg:#EAE6DA","data-bg:#D1B894","data-bg:#BCBCBE","data-bg:#A9754F","data-bg:#ECDB54","data-bg:#E94B3C","data-bg:#6F9FD8","data-bg:#944743","data-bg:#DBB1CD","data-bg:#EC9787","data-bg:#00A591","data-bg:#6C4F3D","data-bg:#EADEDB","data-bg:#BC70A4","data-bg:#BFD641","data-bg:#2E4A62","data-bg:#B4B7BA","data-bg:#C0AB8E","data-bg:#F0EDE5","data-bg:#92B558","data-bg:#DC4C46","data-bg:#672E3B","data-bg:#F3D6E4","data-bg:#C48F65","data-bg:#223A5E","data-bg:#898E8C","data-bg:#005960","data-bg:#9C9A40","data-bg:#4F84C4","data-bg:#D2691E","data-bg:#578CA9","data-bg:#F6D155","data-bg:#004B8D","data-bg:#F2552C","data-bg:#95DEE3","data-bg:#EDCDC2","data-bg:#CE3175","data-bg:#5A7247","data-bg:#CFB095","data-bg:#4C6A92","data-bg:#92B6D5","data-bg:#838487","data-bg:#B93A32","data-bg:#AF9483","data-bg:#AD5D5D","data-bg:#006E51","data-bg:#D8AE47","data-bg:#9E4624","data-bg:#B76BA3","data-bg:#F7CAC9","data-bg:#F7786B","data-bg:#91A8D0","data-bg:#034F84","data-bg:#98DDDE","data-bg:#9896A4","data-bg:#B18F6A","data-bg:#FAE03C","data-bg:#79C753","data-bg:#88B04B","data-bg:#955251","data-bg:#B565A7","data-bg:#009B77","data-bg:#DD4124","data-bg:#D65076","data-bg:#45B8AC","data-bg:#EFC050","data-bg:#5B5EA6","data-bg:#9B2335","data-bg:#DFCFBE","data-bg:#55B4B0","data-bg:#E15D44","data-bg:#7FCDCD","data-bg:#BC243C","data-bg:#C3447A","data-bg:#98B4D4"];

    	/*for($i=0; $i<count($checkboxesColors); $i++){
    		$newStyle = new ElementStyle;
    		$newStyle["type"] = /*"toggle-switch"*//* "checkbox";
    		$newStyle["total_usage"] = 0;
            $newStyle["category"] = "colors";
    		$newStyle->save();

    		$newStyleCss = new ElementStyleCss;
    		$newStyleCss["style_id"] = $newStyle->id;
    		$newStyleCss["type"] = "checkbox";
    		$newStyleCss["for_element"] = "span";
    		$newStyleCss["css_changes"] = "background-color: ".$colors[$i].";";
    		$newStyleCss->save();

            $newStyleAttr = new ElementStyleAttr;
            $newStyleAttr["style_id"] = $newStyle->id;
            $newStyleAttr["type"] = /*"toggle-switch"*/ /*"checkbox";
            $newStyleAttr["for_element"] = "element";
            $newStyleAttr["attributes"] = $checkboxesColors[$i];
            $newStyleAttr->save();
    	}*/

    	return response("Function Disabled");
        //return response("Success");
    }
}
