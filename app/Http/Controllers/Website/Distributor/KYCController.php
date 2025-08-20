<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KYCController extends Controller
{
    public function updatePersonalDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pincode' => 'required|string|max:10',
            'locality' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'mobile' => 'required|string|max:15',
            'occupation' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'nominee' => 'required|string|max:255',
            'relation' => 'required|string|max:50',
            'gender' => 'required|string|in:male,female,other',
        ], [
            'name.required' => 'Full name is required',
            'name.max' => 'Full name cannot exceed 255 characters',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'pincode.required' => 'PIN code is required',
            'locality.required' => 'Locality is required',
            'district.required' => 'City/District is required',
            'state.required' => 'State is required',
            'address.required' => 'Address is required',
            'mobile.required' => 'Mobile number is required',
            'occupation.required' => 'Occupation is required',
            'dob.required' => 'Date of birth is required',
            'dob.before' => 'Date of birth must be in the past',
            'nominee.required' => 'Nominee name is required',
            'relation.required' => 'Relation is required',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Please select a valid gender',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        try {
            $user = Auth::user();

            $kyc = UserKyc::where('userId', $user->user_id)->first();
            if (empty($kyc)) {
                $kyc = new UserKyc();
            }

            $kyc->userId = $user->user_id;
            $kyc->name = $request->name;
            $kyc->pincode = $request->pincode;
            $kyc->locality = $request->locality;
            $kyc->district = $request->district;
            $kyc->state = $request->state;
            $kyc->address = $request->address;
            $kyc->mobile = $request->mobile;
            $kyc->work = $request->occupation;
            $kyc->dob = $request->dob;
            $kyc->nominee = $request->nominee;
            $kyc->relation = $request->relation;
            $kyc->gender = $request->gender;
            $kyc->personal_flag = 1;
            if ($kyc->save()) {
                return $this->successMsg('Personal details updated successfully!');
            }

            return $this->failMsg('Personal details updated failed!');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateBankDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holder_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:50',
            'confirm_account_no' => 'required|same:account_no',
            'ifsc_code' => 'required|string|max:20',
            'branch' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
        ], [
            'holder_name.required' => 'Account holder name is required',
            'account_no.required' => 'Account number is required',
            'confirm_account_no.required' => 'Please confirm your account number',
            'confirm_account_no.same' => 'Account numbers do not match',
            'ifsc_code.required' => 'IFSC code is required',
            'branch.required' => 'Branch name is required',
            'bank.required' => 'Bank name is required',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        try {
            $user = Auth::user();

            $kyc = UserKyc::where('userId', $user->user_id)->first();
            if (empty($kyc)) {
                $kyc = new UserKyc();
            }

            $kyc->userId = $user->user_id;
            $kyc->holder_name = $request->holder_name;
            $kyc->ac_number = $request->account_no;
            $kyc->ifsc_code = $request->ifsc_code;
            $kyc->branch = $request->branch;
            $kyc->bank = $request->bank;
            $kyc->bank_flag = 1;
            if ($kyc->save()) {
                return $this->successMsg('Bank details updated successfully!');
            }

            return $this->failMsg('Bank details updated failed!');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateKYCDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pan_numer' => 'required|string|max:20',
            'pan_docs' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_no' => 'required|string|max:20',
            'aadhar_docs' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'selfi' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'pan_numer.required' => 'PAN number is required',
            'pan_docs.required' => 'PAN document is required',
            'pan_docs.image' => 'PAN document must be an image',
            'pan_docs.mimes' => 'PAN document must be JPEG, PNG or JPG',
            'pan_docs.max' => 'PAN document size cannot exceed 2MB',
            'aadhar_no.required' => 'Aadhaar number is required',
            'aadhar_docs.required' => 'Aadhaar document is required',
            'aadhar_docs.image' => 'Aadhaar document must be an image',
            'aadhar_docs.mimes' => 'Aadhaar document must be JPEG, PNG or JPG',
            'aadhar_docs.max' => 'Aadhaar document size cannot exceed 2MB',
            'selfi.required' => 'Selfie photo is required',
            'selfi.image' => 'Selfie photo must be an image',
            'selfi.mimes' => 'Selfie photo must be JPEG, PNG or JPG',
            'selfi.max' => 'Selfie photo size cannot exceed 2MB',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        try {
            $user = Auth::user();

            $panDocPath = null;
            $aadharDocPath = null;
            $selfiePath = null;

            if ($request->hasFile('pan_docs')) {
                $panDocPath = singleFile($request->file('pan_docs'), 'kyc/pan');
            }

            if ($request->hasFile('aadhar_docs')) {
                $aadharDocPath = singleFile($request->file('aadhar_docs'), 'kyc/aadhaar');
            }

            if ($request->hasFile('selfi')) {
                $selfiePath = singleFile($request->file('selfi'), 'kyc/selfie');
            }

            $kyc = UserKyc::where('userId', $user->user_id)->first();
            if (empty($kyc)) {
                $kyc = new UserKyc();
            }

            $kyc->userId = $user->user_id;
            $kyc->pan_number = $request->pan_numer;
            if (!empty($panDocPath)) {
                $kyc->pan = $panDocPath;
            }
            $kyc->aadhar1 = $request->aadhar_no;
            if (!empty($aadharDocPath)) {
                $kyc->aadhar2 = $aadharDocPath;
            }
            if (!empty($selfiePath)) {
                $kyc->self = $selfiePath;
            }
            $kyc->status = 0;
            $kyc->kyc_flag = 1;
            if ($kyc->save()) {
                return $this->successMsg('KYC documents uploaded successfully! Your application is under review.');
            }
            return $this->failMsg('KYC documents uploaded failed!');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function getKYCStatus()
    {
        try {
            $user = Auth::user();
            $kyc = UserKyc::where('userId', $user->user_id)->first();

            return $this->successMsg('KYC status retrieved successfully!', $kyc);
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
}
