<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        try {

            $data['record'] = Merchant::where('user_id', Auth::user()->_id)->first();

            return view('CRM.Profile.index', $data);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    public function saveBusiness(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'business_details.business_name'     => 'required|string|min:2|max:80',
                'business_details.business_type'     => 'nullable|string',
                'business_details.business_phone_no' => 'required|numeric|digits:10|not_in:0',
                'business_details.business_email'    => 'required|email|min:2',
                'business_details.city'              => 'required',
                'business_details.state'             => 'required',
                'business_details.country'           => 'required',
                'business_details.address'           => 'required',
                'business_details.gst_no'            => 'nullable',
                'business_details.registration_no'   => 'nullable',
                'business_details.website_url'       => 'nullable|url'
            ], [
                'business_details.business_name.required'     => 'Business Name Field is required.',
                'business_details.business_name.min'          => 'Business Name must be at least 2 characters.',
                'business_details.business_name.max'          => 'Business Name must not exceed 80 characters.',
                'business_details.business_phone_no.required' => 'Business Phone No Field is required.',
                'business_details.business_phone_no.numeric'  => 'Business Phone No should be numeric.',
                'business_details.business_phone_no.digits'   => 'Business Phone No must be exactly 10 digits.',
                'business_details.business_email.required'    => 'Business Email Field is required.',
                'business_details.business_email.email'       => 'Please provide a valid email address.',
                'business_details.business_email.min'         => 'Business Email must be at least 2 characters.',
                'business_details.city.required'              => 'City Field is required.',
                'business_details.state.required'             => 'State Field is required.',
                'business_details.country.required'           => 'Country Field is required.',
                'business_details.address.required'           => 'Address Field is required.',
                'business_details.website_url.url'            => 'Please provide a valid website URL.'
            ]);

            if ($validator->fails())
                return response(['status' => false, 'validation' => $validator->errors()]);

            $merchant_id = Auth::user()->merchant_id;

            $save = Merchant::find($merchant_id);
            $save->business_details = $request->business_details;
            $save->business_flag = 1;
            if ($save->save())
                return response(['status' => true, 'msg' => 'Business Details Updated Successfully!']);

            return response(['status' => false, 'msg' => 'Something went wrong, Business Details not Updated!']);
        } catch (Exception $e) {
            return response(['status' => true, 'msg' => $e->getMessage()]);
        }
    }


    public function saveBank(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bank_details.holder_name' => 'required|string|min:2|max:80',
                'bank_details.bank_name' => 'required|string|min:2|max:200',
                'bank_details.account_no' => 'required|string|min:8|max:20|regex:/^[0-9]+$/',
                // 'bank_details.ifsc_code' => 'required|string|size:11|regex:/^[A-Za-z]{4}[0-9]{7}$/',
                'bank_details.ifsc_code' => 'required|string',
                'bank_details.type' => 'required|string|in:saving,current,other',
            ], [
                'bank_details.holder_name.required' => 'Account Holder Name is required.',
                'bank_details.holder_name.min' => 'Account Holder Name must be at least 2 characters.',
                'bank_details.holder_name.max' => 'Account Holder Name must not exceed 80 characters.',
                'bank_details.bank_name.required' => 'Bank Name is required.',
                'bank_details.bank_name.min' => 'Bank Name must be at least 2 characters.',
                'bank_details.bank_name.max' => 'Bank Name must not exceed 200 characters.',
                'bank_details.account_no.required' => 'Account Number is required.',
                'bank_details.account_no.min' => 'Account Number must be at least 8 digits.',
                'bank_details.account_no.max' => 'Account Number must not exceed 20 digits.',
                'bank_details.account_no.regex' => 'Account Number should only contain digits.',
                'bank_details.ifsc_code.required' => 'IFSC Code is required.',
                'bank_details.ifsc_code.size' => 'IFSC Code must be exactly 11 characters.',
                'bank_details.ifsc_code.regex' => 'IFSC Code format is invalid. It should be 4 letters followed by 7 digits.',
                'bank_details.type.required' => 'Account Type is required.',
                'bank_details.type.in' => 'Account Type must be one of the following: saving, current, or other.'
            ]);

            if ($validator->fails())
                return response(['status' => false, 'validation' => $validator->errors()]);

            $merchant_id = Auth::user()->merchant_id;

            $bank_details = $request->bank_details;

            $save = Merchant::find($merchant_id);
            $save->bank_details = $bank_details;
            $save->bank_flag = 1;
            if ($save->save())
                return response(['status' => true, 'msg' => 'Bank Details Updated Successfully!']);

            return response(['status' => false, 'msg' => 'Something went wrong, Bank Details not Updated!']);
        } catch (Exception $e) {
            return response(['status' => true, 'msg' => $e->getMessage()]);
        }
    }

    public function saveContactPerson(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'contact_person.name' => 'required|string|min:2|max:80',
                'contact_person.phone_no' => 'required|numeric|digits:10|not_in:0',
                'contact_person.email' => 'required|email|min:2',
                'contact_person.city' => 'required|string',
                'contact_person.state' => 'required|string',
                'contact_person.country' => 'required|string',
                'contact_person.address' => 'required|string',
                'contact_person.designation' => 'nullable|string',
                'contact_person.gender' => 'required|string|in:male,female,other',
                'profile_pic' => 'nullable|mimes:jpeg,png,jpg,gif,svg,bmp,webp|max:5020'
            ],  [
                'contact_person.name.required' => 'Name is required.',
                'contact_person.name.min' => 'Name must be at least 2 characters.',
                'contact_person.name.max' => 'Name must not exceed 80 characters.',
                'contact_person.phone_no.required' => 'Phone No is required.',
                'contact_person.phone_no.numeric' => 'Phone No should be numeric.',
                'contact_person.phone_no.digits' => 'Phone No must be exactly 10 digits.',
                'contact_person.email.required' => 'Email is required.',
                'contact_person.email.email' => 'Please provide a valid email address for Contact Person.',
                'contact_person.email.min' => 'Email must be at least 2 characters.',
                'contact_person.city.required' => 'City is required.',
                'contact_person.state.required' => 'State is required.',
                'contact_person.country.required' => 'Country is required.',
                'contact_person.address.required' => 'Address is required.',
                'contact_person.designation.string' => 'Designation must be a valid string.',
                'contact_person.gender.required' => 'Gender is required.',
                'contact_person.gender.in' => 'Gender must be one of the following: male, female, or other.',
                'profile_pic.mimes' => 'The Profile Picture must be an image of type: jpeg, png, jpg, gif, svg, bmp, or webp.',
                'profile_pic.max' => 'The Profile Picture must not exceed 5MB.',
            ]);

            if ($validator->fails())
                return response(['status' => false, 'validation' => $validator->errors()]);

            $contact_person = $request->contact_person;
            if ($request->hasFile('profile_pic')) {
                $image_path = singleFile($request->file('profile_pic'), 'merchant');
                $contact_person['profile_pic'] = $image_path;
            }

            $merchant_id = Auth::user()->merchant_id;

            $save = Merchant::find($merchant_id);
            $save->contact_person = $contact_person;
            $save->contact_person_flag = 1;
            if ($save->save())
                return response(['status' => true, 'msg' => 'Contact Person Details Updated Successfully!']);

            $this->user(['name' => $save->contact_person['name'], 'phone_no' => $save->contact_person['phone_no'], 'profile_pic' => $save->contact_person['profile_pic']]);
            return response(['status' => false, 'msg' => 'Something went wrong, Contact Person Details not Updated!']);
        } catch (Exception $e) {
            return response(['status' => true, 'msg' => $e->getMessage()]);
        }
    }

    private function user($request)
    {
        $request = (object)$request;
        $user = User::find(Auth::user()->_id);
        $user->name = $request->name;
        $user->profile_pic = $request->profile_pic;
        $user->save();
    }


    public function saveKyc(Request $request)
    {
        try {

            $rules = [
                'kyc_details.id_type'        => 'required|string|min:2|max:80',
                'kyc_details.pancard_no'     => 'required|string|not_in:0',
                'pancard_docs'               => 'required|file|max:5020',
                'business_registration_docs' => 'required|file|max:5020',
                'address_proff'              => 'required|file|max:5020',
            ];

            if (!empty($request->kyc_details['id_type']) && $request->kyc_details['id_type'] == 'aadhar') {
                $rules['aadhar_docs_1'] = 'required|file|max:5020';
                $rules['aadhar_docs_2'] = 'required|file|max:5020';
            } else {
                $rules['id_docs'] = 'required|file|max:5020';
            }

            $validator = Validator::make(
                $request->all(),
                $rules,
                [
                    'kyc_details.id_type.required' => 'ID Type is required.',
                    'kyc_details.id_type.min' => 'ID Type must be at least 2 characters.',
                    'kyc_details.id_type.max' => 'ID Type must not exceed 80 characters.',

                    'kyc_details.pancard_no.required' => 'PAN Card Number is required.',
                    'kyc_details.pancard_no.not_in' => 'PAN Card Number cannot be 0.',

                    'pancard_docs.required' => 'PAN Card document is required.',
                    'pancard_docs.file' => 'PAN Card document must be a valid file.',
                    'pancard_docs.max' => 'PAN Card document must not exceed 5MB in size.',

                    'business_registration_docs.required' => 'Business Registration document is required.',
                    'business_registration_docs.file' => 'Business Registration document must be a valid file.',
                    'business_registration_docs.max' => 'Business Registration document must not exceed 5MB in size.',

                    'address_proff.required' => 'Address Proof is required.',
                    'address_proff.file' => 'Address Proof must be a valid file.',
                    'address_proff.max' => 'Address Proof must not exceed 5MB in size.',

                    'aadhar_docs_1.required' => 'First Aadhar document is required.',
                    'aadhar_docs_1.file' => 'First Aadhar document must be a valid file.',
                    'aadhar_docs_1.max' => 'First Aadhar document must not exceed 5MB in size.',

                    'aadhar_docs_2.required' => 'Second Aadhar document is required.',
                    'aadhar_docs_2.file' => 'Second Aadhar document must be a valid file.',
                    'aadhar_docs_2.max' => 'Second Aadhar document must not exceed 5MB in size.',

                    'id_docs.required' => 'ID Document is required.',
                    'id_docs.file' => 'ID Document must be a valid file.',
                    'id_docs.max' => 'ID Document must not exceed 5MB in size.',
                ]
            );

            if ($validator->fails())
                return response(['status' => false, 'validation' => $validator->errors()]);

            $kyc_details = $request->kyc_details;
            if ($request->hasFile('id_docs')) {
                $file_path = singleFile($request->file('id_docs'), 'kyc');
                $kyc_details['id_docs'] = $file_path;
            }

            if ($request->hasFile('aadhar_docs_1')) {
                $file_path = singleFile($request->file('aadhar_docs_1'), 'kyc');
                $kyc_details['aadhar_docs_1'] = $file_path;
            }

            if ($request->hasFile('aadhar_docs_2')) {
                $file_path = singleFile($request->file('aadhar_docs_2'), 'kyc');
                $kyc_details['aadhar_docs_2'] = $file_path;
            }

            if ($request->hasFile('pancard_docs')) {
                $file_path = singleFile($request->file('pancard_docs'), 'kyc');
                $kyc_details['pancard_docs'] = $file_path;
            }

            if ($request->hasFile('business_registration_docs')) {
                $file_path = singleFile($request->file('business_registration_docs'), 'kyc');
                $kyc_details['business_registration_docs'] = $file_path;
            }

            if ($request->hasFile('address_proff')) {
                $file_path = singleFile($request->file('address_proff'), 'kyc');
                $kyc_details['address_proff'] = $file_path;
            }

            $merchant_id = Auth::user()->merchant_id;

            $save = Merchant::find($merchant_id);
            $save->kyc_details = $kyc_details;
            $save->kyc_flag = 1;
            if ($save->save())
                return response(['status' => true, 'msg' => 'Kyc Details Updated Successfully!']);

            return response(['status' => false, 'msg' => 'Something went wrong, Kyc Details not Updated!']);
        } catch (Exception $e) {
            return response(['status' => true, 'msg' => $e->getMessage()]);
        }
    }
}
