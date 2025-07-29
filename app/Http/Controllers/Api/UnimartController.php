<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Unimart;
use App\Models\UnimartAddress;
use Exception;

class UnimartController extends Controller
{
    public function index(Request $request)
    {
        try {
            $records = Unimart::with('UniMartAdd')->where('status', 1)->get()->map(function ($record) {
                return [
                    'umart_id'   => $record->alpha_num,
                    'name'       => $record->name,
                    'mobile'     => $record->mobile,
                    'email'      => $record->email,
                    'pincode'    => $record->UniMartAdd->pincode ?? '',
                    'state'      => $record->UniMartAdd->state ?? '',
                    'city'       => $record->UniMartAdd->city ?? '',
                    'locality'   => $record->UniMartAdd->locality ?? ''
                ];
            });
            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}
