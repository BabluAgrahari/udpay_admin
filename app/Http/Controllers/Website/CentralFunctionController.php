<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use Illuminate\Http\Request;
use App\Models\IFSC;

class CentralFunctionController extends Controller
{
    public function getPincode($pincode)
    {
        if(empty($pincode)){
            return $this->failRes('Pincode is required');
        }
        $pincode = Pincode::where('pincode', $pincode)->first();
        if (empty($pincode))
            return $this->notFoundRes();

        $record = [
            'pincode' => (int) $pincode->pincode ?? '',
            'office_name' => $pincode->office_name ?? '',
            'district' => $pincode->District ?? '',
            'state' => $pincode->StateName ?? ''
        ];
        return $this->recordRes($record);
    }

    public function getIFSC($ifsc)
    {
        if(empty($ifsc)){
            return $this->failRes('IFSC is required');
        }
        $ifsc = IFSC::where('ifsc', $ifsc)->first();
        if (empty($ifsc))
            return $this->notFoundRes();

        $record = [
            'ifsc' => $ifsc->ifsc ?? '',
            'branch' => $ifsc->branch ?? '',
            'bank' => $ifsc->bank ?? ''
        ];
        return $this->recordRes($record);
    }
}
