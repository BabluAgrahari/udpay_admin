<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Sliders;
use Exception;

class AppBanner extends Controller
{
    public function getSlider(Request $request)
    {
        try {

            $a = array('pro_cat', 'deals', 'app_offer', 'app_main', 'fina_main', 'insurance_main', 'bus_banner', 'unilearn_main', 'recharge_main', 'education_main', 'legal_main');
            foreach ($a as $v) {
                $arr[$v] = [];
                $q = Sliders::where('status', 1)->where('slider_type', $v)->orderBy('id', 'desc')->get();
                if ($q)
                    foreach ($q as $s) {
                        $url = $s->slider_image;
                        $arr[$v][] = array('img_url' => $url, 'cat_id' => $s->cat_id);
                    }
            }
            return $this->recordRes($arr);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}
