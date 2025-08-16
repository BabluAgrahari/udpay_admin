<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function contactUs(Request $request)
    {
        try {
            $data = config('accountApi.contact_us');
            return $this->successRes('Contact us data fetched successfully', $data);
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function faq(Request $request)
    {
        try {
            $data = config('accountApi.faq');
            return $this->successRes('FAQ data fetched successfully', $data);
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function privacyPolicy(Request $request)
    {
        try {
            $data = config('accountApi.privacy_policy');
            return $this->successRes('Privacy policy data fetched successfully', $data);
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function download(Request $request)
    {
        try {
            $data = config('accountApi.download');
            return $this->successRes('Download data fetched successfully', $data);
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}