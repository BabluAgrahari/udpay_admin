<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\UserKyc;
use App\Models\User;

class KYCController extends Controller
{
    /**
     * Update Personal Details
     */
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
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Update or create KYC record
            $kyc = UserKyc::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $request->name,
                    'pincode' => $request->pincode,
                    'locality' => $request->locality,
                    'district' => $request->district,
                    'state' => $request->state,
                    'address' => $request->address,
                    'mobile' => $request->mobile,
                    'work' => $request->occupation,
                    'dob' => $request->dob,
                    'nominee' => $request->nominee,
                    'relation' => $request->relation,
                    'gender' => $request->gender,
                ]
            );

            // Update user email if changed
            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $user->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Personal details updated successfully!',
                'data' => $kyc
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong! Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Bank Details
     */
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
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Update or create KYC record
            $kyc = UserKyc::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'holder_name' => $request->holder_name,
                    'ac_number' => $request->account_no,
                    'ifsc_code' => $request->ifsc_code,
                    'branch' => $request->branch,
                    'bank' => $request->bank,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Bank details updated successfully!',
                'data' => $kyc
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong! Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update KYC Documents
     */
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
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Handle file uploads
            $panDocPath = null;
            $aadharDocPath = null;
            $selfiePath = null;

            if ($request->hasFile('pan_docs')) {
                $panDocPath = $request->file('pan_docs')->store('kyc/pan', 'public');
            }

            if ($request->hasFile('aadhar_docs')) {
                $aadharDocPath = $request->file('aadhar_docs')->store('kyc/aadhaar', 'public');
            }

            if ($request->hasFile('selfi')) {
                $selfiePath = $request->file('selfi')->store('kyc/selfie', 'public');
            }

            // Update or create KYC record
            $kyc = UserKyc::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'pan_number' => $request->pan_numer,
                    'pan_document' => $panDocPath,
                    'aadhaar_number' => $request->aadhar_no,
                    'aadhaar_document' => $aadharDocPath,
                    'selfie' => $selfiePath,
                    'status' => 'pending', // Set status to pending for review
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'KYC documents uploaded successfully! Your application is under review.',
                'data' => $kyc
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong! Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get KYC Status
     */
    public function getKYCStatus()
    {
        try {
            $user = Auth::user();
            $kyc = UserKyc::where('user_id', $user->id)->first();

            return response()->json([
                'status' => true,
                'data' => $kyc
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
