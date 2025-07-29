<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CuelinksController extends Controller
{
    public function Campaigns(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.cuelinks.com/api/v2/campaigns.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Token token=qdQ7hvRPrarZxeKD248AR8iopW7CuwVsRuZDW_Ivk6Q",
        "Content-Type: application/json"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }

    public function CampaignsCount(Request $request){
        
        //sort_column=id&sort_direction=asc&page=1&per_page=50&search_term=Flipkart&country_id=252
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.cuelinks.com/api/v2/campaigns.json?sort_column=".$request->sort_column."&sort_direction=".$request->sort_direction."&page=".$request->page."&per_page=".$request->per_page."&search_term=".$request->search_term."&country_id=".$request->country_id."");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Token token=qdQ7hvRPrarZxeKD248AR8iopW7CuwVsRuZDW_Ivk6Q",
        "Content-Type: application/json"
        ));
        //echo "https://www.cuelinks.com/api/v2/campaigns.json?sort_column=".$request->sort_column."&sort_direction=".$request->sort_direction."&page=".$request->page."&per_page=".$request->per_page."&search_term=".$request->search_term."&country_id=".$request->country_id."";
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
    
}
