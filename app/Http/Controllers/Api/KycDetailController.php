<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Models\DigilockerCode;
use App\Models\Wallet;
use App\Services\Kyc\Digilocker;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Validation\KycValidation;
use App\Http\Validation\BankValidation;
use App\Http\Validation\KYCDocsValidation;
use App\Models\UserKyc;
use Exception;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Models\Pincode;
use App\Models\Ifsc;
use App\Models\User;

class KycDetailController extends Controller
{
    public function personalDetails(KycValidation $request)
    {
        try {
            $user = User::select('user_nm')->where('user_id', Auth::user()->user_id)->first();
            $save = UserKyc::where('userId', Auth::user()->user_id)->first();
            if (empty($save))
                $save = new UserKyc();

            $save->name = $request->name;
            $save->mobile = $request->mobile;
            $save->address = $request->address;
            $save->state = $request->state;
            $save->district = $request->district;
            $save->pincode = $request->pincode;
            $save->personal_flag = 1;
            $save->dob = $request->dob;
            $save->gender = $request->gender;
            $save->work = $request->work;
            $save->nominee = $request->nominee;
            $save->relation = $request->relation;
            $save->userId = Auth::user()->user_id;
            $save->unm = $user->user_nm;
            if (!$save->save())
                return $this->failRes('Something Went wrong, Profile not updated.');

            return $this->successRes('Personal Details Updated Successfully.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function keyDocs(KYCDocsValidation $request)
    {
        try {
            $kyc = UserKyc::where('userId', Auth::user()->user_id)->first();
            if (empty($kyc))
                $kyc = new UserKyc();

            if ($request->docs_type == 'pan') {
                $countPan = UserKyc::where('pan_number', $request->pan_number)->where('userId', '!=', Auth::user()->user_id)->count();
                if ($countPan > 0)
                    return $this->failRes('This Pan Number used by other user.');

                $kyc->pan_number = $request->pan_number;
                $kyc->pan_flag = 1;
            } elseif ($request->docs_type == 'aadhar') {
                $countAadhar = UserKyc::where('aadhar_number', $request->aadhar_number)->where('userId', '!=', Auth::user()->user_id)->count();
                if ($countAadhar > 0)
                    return $this->failRes('This Aadhar Number used by other user.');
                $kyc->aadhar_number = $request->aadhar_number;
                $kyc->doc_type = $request->type;
                $kyc->aadhar_flag = 1;
            } elseif ($request->docs_type == 'self') {
                $kyc->self_flag = 1;
            }

            $kyc->kyc_flag = 0;
            if ($kyc->self_flag && $kyc->aadhar_flag && $kyc->pan_flag)
                $kyc->kyc_flag = 1;

            if ($request->hasFile('pan_image')) {
                $file = $request->file('pan_image');
                $kyc->pan = singleFile($file, 'kyc/pan');
            }

            if ($request->hasFile('front_aadhar_image')) {
                $file = $request->file('front_aadhar_image');
                $kyc->aadhar1 = singleFile($file, 'kyc/aadhar');
            }

            if ($request->hasFile('back_aadhar_image')) {
                $file = $request->file('back_aadhar_image');
                $kyc->aadhar2 = singleFile($file, 'kyc/aadhar');
            }

            if ($request->hasFile('self')) {
                $file = $request->file('self');
                $kyc->self = singleFile($file, 'kyc/self');
            }
            $kyc->userId = Auth::user()->user_id ?? '';

            if ($kyc->save())
                return $this->successRes($request->docs_type . ' Details Updated Successfully.');

            return $this->failRes('Something Went wrong.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function bankDetails(BankValidation $request)
    {
        try {

            $save = UserKyc::where('userId', Auth::user()->user_id)->first();
            if (empty($save))
                $save = new UserKyc();

            $save->ac_number = $request->account_number;
            $save->id_proof = $request->id_proof;
            $save->holder_name = $request->holder_name;
            $save->ifsc_code = $request->ifsc_code;
            $save->branch = $request->branch;
            $save->bank = $request->bank;
            $save->userId = Auth::user()->user_id ?? '';
            $save->bank_flag = 1;

            if ($request->hasFile('bank_docs')) {
                $file = $request->file('bank_docs');
                $save->bank_doc = singleFile($file, 'kyc/bank');
            }

            if ($save->save())
                return $this->successRes('Bank Details Updated Successfully.');

            return $this->failRes('Something Went wrong.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
    public function compressImage()
    {
        // Path to the original image file
        $filePath = public_path('kyc/bank/');
        // Load the image
        $image = Image::make($filePath);
        // Compress the image (adjust the quality as needed)
        $image->encode('jpg', 70) // Use 'jpg' or 'png' based on the format
            ->save(public_path('kyc/bank/')); // Save the compressed image
        //print_r($image);die;
        return $image;
    }

    public function pincodeDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pincode' => 'required|numeric|digits:6',
        ]);
        if ($validator->fails())
            return $this->validationRes($validator->messages());

        $pincode = Pincode::where('pincode', $request->pincode)->first();
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

    public function ifscDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ifsc_code' => 'required|string',
        ]);
        if ($validator->fails())
            return $this->validationRes($validator->messages());

        $ifsc = Ifsc::where('ifsc', $request->ifsc_code)->first();
        if (empty($ifsc))
            return $this->notFoundRes();

        $record = [
            'ifsc' => $ifsc->ifsc ?? '',
            'branch' => $ifsc->branch ?? '',
            'bank' => $ifsc->bank ?? ''
        ];
        return $this->recordRes($record);
    }

    public function digitalKyc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documents' => 'required|string|in:pan,aadhaar',
            // 'pan_name' => 'nullable|string',
            'pan_no' => 'required|string',
            'aadhar_no' => 'required|string',
            "occupation" => 'required|string',
            "nominee" => 'required|string',
            "mobile_no" => 'required|numeric|digits:10|not_in:0'

        ]);
        if ($validator->fails())
            return $this->validationRes($validator->errors());

        $userId = Auth::user()->user_id;
        $transID = date('ymdhs') . rand(1111, 4444);
        $unm = Auth::user()->user_nm;
        $checkWallet = $this->debitUserWallet($unm, 5, 0, $userId, $transID, true); // for check balance is avaliable or not
        if (!$checkWallet['status'])
            return $this->failRes($checkWallet['msg'] ?? '');

        $payload = [
            'documents' => $request->documents,
            'pan_name' => $request->pan_name ?? '',
            'pan_no' => $request->pan_no
        ];

        $digilocket = new Digilocker();
        $response = $digilocket->generateDigilockerUrl($payload);

        if (empty($response['status']) || $response['status'] === false) {
            return $this->failRes($response['msg']);
        }

        $save = new DigilockerCode();
        $save->user_id = Auth::user()->user_id;
        $save->user_nm = Auth::user()->user_nm;
        $save->client_token = $response['client_token'];
        $save->state = $response['state'];
        $save->unique_code = $response['unique_code'];
        $save->redirect_url = $response['url'];
        if ($save->save()) {

            $kyc = UserKyc::find($save->user_id);
            $kyc->aadhar_number = $request->aadhar_no;
            $kyc->pan_number = $request->pan_no;
            $kyc->occupation = $request->occupation;
            $kyc->nominee = $request->nominee;
            $kyc->mobile_no = $request->mobile_no;
            $kyc->save();

            $checkWallet = $this->debitUserWallet($unm, 5, 0, $userId, $transID, false); // for check balance is avaliable or not
            return $this->successRes('KYC Verification URL Generated Successfully.', [
                'url' => $response['url'],
            ]);

        }

        return $this->failRes('Something Went wrong, KYC Verification URL not generated.');

    }

    public function digilockerRedirect($uniqueCode)
    {
        $digilockerTab = DigilockerCode::where('unique_code', $uniqueCode)->first();
        if (empty($digilockerTab)) {
            Log::warning('Digilocker code not found for unique code: ' . $uniqueCode);
            return redirect()->to('/error')->with('error', 'Invalid Digilocker code.');
        }
        $payload = [
            'state' => $digilockerTab->state,
            'client_token' => $digilockerTab->client_token,
        ];

        $digilocker = new Digilocker();

        $response = $digilocker->retriveKycDetails($payload);
        if (empty($response['status']) || $response['status'] === false) {
            Log::warning('Digilocker redirect failed: ' . $response['msg']);
            return redirect()->to('/error')->with('error', $response['msg']);
        }

        $reqeust = $response['data'];

        if (empty($reqeust)) {
            Log::warning('Digilocker redirect response data is empty for unique code: ' . $uniqueCode);
            return redirect()->to('/error')->with('error', 'No data received from Digilocker.');
        }
        $request = (object) $reqeust;

        $userId = $digilockerTab->user_id;
        $user = User::select('user_nm')->where('user_id', $userId)->first();
        $save = UserKyc::where('userId', $userId)->first();
        if (empty($save))
            $save = new UserKyc();

        $save->name = $request->name ?? '';
        $save->address = $request->aadhar_address ?? '';
        $save->state = $request->state ?? '';
        $save->district = $request->dist ?? '';
        $save->pincode = $request->pincode ?? '';
        $save->personal_flag = 1;
        $save->work = $request->home ?? '';
        $save->dob = $request->dob ?? '';
        $save->gender = $request->gender ?? '';
        $save->userId = $userId;
        $save->unm = $user->user_nm;
        if ($save->save()) {

            $kyc = UserKyc::find($save->id);
            if (!empty($request->aadhar_no)) {
                // $kyc->aadhar_number = $request->aadhar_no;
                $kyc->doc_type = 'addhar';
                $kyc->aadhar1 = $request->adharimg ?? '';
                $kyc->aadhar2 = $request->adharimg ?? '';
                $kyc->aadhar_flag = 1;
            }

            $kyc->kyc_flag = 2;
            $kyc->save();

            //for delete
            DigilockerCode::where('unique_code', $uniqueCode)->forceDelete();
            return redirect()->to('/kyc-success')->with('success', 'KYC Details Updated Successfully.');
        }

        return redirect()->to('/error')->with('error', 'Something Went wrong, KYC Details not updated.');

    }

    private function debitUserWallet($unm, $amount, $bp, $user_id, $order_id, $checkblance = false)
    {
        $userWalletAmt = $toatlBalance = 0;
        $userWallet = Wallet::where('userid', $user_id)->first();
        $unicashAmt = 0;
        $amtAdd = 0;
        if ($userWallet->earning >= $amount) {

            $userWalletAmt = $userWallet->earning;
            $effectAmt = $userWallet->earning - $amount;
            $earningAmt = $amount;
            $toatlBalance = $effectAmt + $userWallet->amount + $userWallet->unicash;
            // $userWalletData        = array('earning' => $effectAmt, 'bp' => $userWallet->bp - $bp);

            $userWallet->earning = $effectAmt;
            $userWallet->bp = $userWallet->bp - $bp;
        } else if ($userWallet->unicash + $userWallet->earning >= $amount) {

            $remainAmt = $amount - $userWallet->earning;
            $userWalletAmt = $userWallet->earning + $userWallet->unicash;
            $effect_amt = $userWallet->unicash + $userWallet->earning - $amount;
            $earningAmt = $userWallet->earning;
            $unicashAmt = $remainAmt;
            $toatlBalance = $effect_amt + $userWallet->amount;
            // $userWalletData        = array('earning' => 0, 'unicash' => ($userWallet->unicash - $remainAmt), 'bp' => $userWallet->bp - $bp);

            $userWallet->earning = 0;
            $userWallet->unicash = ($userWallet->unicash - $remainAmt);
            $userWallet->bp = $userWallet->bp - $bp;
        } else if ($userWallet->earning + $userWallet->unicash + $userWallet->amount >= $amount) {

            $remainAmt = $amount - $userWallet->earning;
            $amtAdd = $remainAmt - $userWallet->unicash;
            $senderAmount = $userWallet->amount - $amtAdd;
            $userWalletAmt = $userWallet->earning + $userWallet->unicash + $userWallet->amount;
            $earningAmt = $userWallet->earning;
            $unicashAmt = $userWallet->unicash;
            $toatlBalance = $userWallet->unicash + $userWallet->earning + $userWallet->amount - $amount;
            // $userWalletData = array('earning' => 0, 'unicash' => 0, 'amount' => $senderAmount, 'bp' => $userWallet->bp - $bp);

            $userWallet->earning = 0;
            $userWallet->unicash = 0;
            $userWallet->amount = $senderAmount;
            $userWallet->bp = $userWallet->bp - $bp;
        }

        if ($userWalletAmt >= $amount) {
            if ($checkblance)
                return ['status' => true, 'msg' => 'Insufficient Balance.'];

            $userWallet->save();
            //Wallet::where('userid', $user_id)->update($userWalletData);
            $transPayload = [
                'unm' => $unm,
                'user_id' => $user_id,
                'transition_type' => 'Recharge Mobile',
                'debit' => $amount,
                'balance' => $toatlBalance,
                'in_type' => 'Your Wallet is Debited ' . $amount . ' for KYC Verify to UNI' . $order_id,
                'description' => 'amount : ' . $amtAdd . ' unicash: ' . $unicashAmt . ' earning : ' . $earningAmt . ' uniPoint: ' . $userWallet->bp,
                'amount' => $amtAdd,
                'unicash' => $unicashAmt,
                'earning' => $earningAmt,
                'unipoint' => $bp,
                'order_id' => $order_id
            ];

            if (walletTransaction($transPayload)) {
                return ['status' => true, 'msg' => 'Wallet updated Successfully.'];
            } else {
                return ['status' => false, 'msg' => 'Amount not debit.'];
            }
        } else {

            Log::info("KYC Verify amount $amount user wallet $userWallet->earning + $userWallet->unicash + $userWallet->amount[$user_id]");
            return ['status' => false, 'msg' => 'insufficent Amount.'];

        }
    }

}
