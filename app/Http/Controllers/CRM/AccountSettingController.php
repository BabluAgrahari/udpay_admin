<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Validation\JobDetailsValidation;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Auth;
use App\Imports\LeadImport;
use App\Models\LeadActivity;
use App\Models\AccountSetting;
use App\Models\User;
use App\Models\Manager;
use App\Models\CallerAgentLead;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;

class AccountSettingController extends Controller
{
    public function index(Request $request)
    {
        try {

            $data['record'] = AccountSetting::where('user_id', Auth::user()->_id)->first();
            $data['user'] = User::find(Auth::user()->_id);
            return view('CRM.AccountSetting.webhook_key', $data);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function saveWebhook(Request $request)
    {
        try {
            if (!isVerified())
                return response(['status' => false, 'msg' => 'Inactive Account, Please Complete Your KYC or contact to Admin.']);

            $save = AccountSetting::where('user_id', Auth::user()->_id)->first();
            if (empty($save))
                return response(['status' => false, 'msg' => 'Something went wrong, Please Contact to Admin']);

            $save->webhook_url = $request->webhook_url;
            $save->paymetn_link_webhook_url = $request->paymetn_link_webhook_url;
            $save->ip_address  = $request->ip_address;
            if ($save->save())
                return response(['status' => true, 'msg' => 'IP Updated Successfully.', 'webhook_url' => $save->webhook_url ?? '', 'ip_address' => $save->ip_address]);

            return response(['status' => false, 'msg' => 'IP not Updated.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }


    public function resetKey(Request $request)
    {
        try {
            if (!isVerified())
                return response(['status' => false, 'msg' => 'Inactive Account, Please Complete Your KYC or contact to Admin.']);

            $save = User::where('_id', Auth::user()->_id)->first();
            if (empty($save))
                return response(['status' => false, 'msg' => 'Something went wrong, Please Contact to Admin']);

            // $save->client_id    = uniqCode(10) . Auth::user()->_id;
            $save->secret_key  = uniqCode(40) . Auth::user()->_id;
            $save->encryption_key = uniqid(25) . Auth::user()->_id;
            if ($save->save())
                return response(['status' => true, 'msg' => 'Key Reset Successfully.', 'client_id' => $save->client_id, 'secret_key' => $save->secret_key, 'encryption_key' => $save->encryption_key]);

            return response(['status' => false, 'msg' => 'Key not Reset.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function api()
    {
        return view('CRM.AccountSetting.api');
    }
}
