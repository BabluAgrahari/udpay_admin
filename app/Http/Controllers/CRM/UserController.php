<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserKyc;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->perPage ?? config('global.perPage');
            $query = User::with('kyc')->where('role','customer');

            // Apply filters
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->has('kyc_status')) {
                switch ($request->kyc_status) {
                    case 'with_kyc':
                        $query->withKyc();
                        break;
                    case 'without_kyc':
                        $query->withoutKyc();
                        break;
                    case 'verified':
                        $query->kycVerified();
                        break;
                    case 'pending':
                        $query->kycPending();
                        break;
                }
            }

            if ($request->has('status')) {
                $query->where('isactive', $request->status);
            }

            $data['records'] = $query->paginate($perPage);
            return view('CRM.User.index', $data);
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function create()
    {
        // try {
            // $this->store();
            return view('CRM.User.create');
        // } catch (Exception $e) {
        //     abort(500, $e->getMessage());
        // }
    }

    public function store()
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     // 'first_name' => 'required|string|max:100',
            //     // 'last_name' => 'required|string|max:100',
            //     'email' => 'required|email|unique:customers,email',
            //     // 'mobile' => 'required|string|max:20|unique:customers,mobile',
            //     'password' => 'required|string|min:6',
            //     // 'role' => 'required|string|in:admin,customer,merchant',
            //     'dob' => 'nullable|date',
            //     'gender' => 'nullable|string|in:male,female,other',
            //     // 'status' => 'nullable|boolean'
            // ]);

            // if ($validator->fails()) {
            //     return response(['status' => false, 'msg' => $validator->errors()->first()]);
            // }

            $customer = new User();
            $customer->first_name = 'Ravi';
            $customer->last_name = 'Kumar';
            $customer->email = 'ravi@gmail.com';
            $customer->mobile = '8899121012';
            $customer->password = bcrypt('123456');
            $customer->role = 'customer';
            $customer->dob = '1990-01-01';
            $customer->gender = 'make';
            $customer->isactive =1;

            if ($customer->save()) {
                return response(['status' => true, 'msg' => 'Customer Created Successfully.']);
            }

            return response(['status' => false, 'msg' => 'Something Went wrong, Customer Not Created.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('kyc')->findOrFail($id);
            return view('CRM.User.show', compact('user'));
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = User::with('kyc')->findOrFail($id);
           
            return view('CRM.User.edit', compact('user'));
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                // 'mobile' => 'required|string|max:20|unique:customers,mobile,' . $id,
                'dob' => 'nullable|date',
                'gender' => 'nullable|string|in:male,female,other',
                'status' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            $customer = User::findOrFail($id);
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->mobile = $request->mobile;
            $customer->dob = $request->dob;
            $customer->gender = $request->gender;
            $customer->isactive = $request->status ? 1 : 0;

            if ($customer->save()) {
                return response(['status' => true, 'msg' => 'Customer Updated Successfully.']);
            }

            return response(['status' => false, 'msg' => 'Something Went wrong, Customer Not updated.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function kyc($id)
    {
        try {
            $user = User::with('kyc')->findOrFail($id);
            return view('CRM.User.kyc', compact('user'));
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function storeKyc(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'mobile_no' => 'required|string|max:20',
                'pan_no' => 'nullable|string|max:20',
                'aadhar_no' => 'nullable|string|max:20',
                'dob' => 'required|date',
                'gender' => 'required|string|in:male,female,other',
                'address' => 'required|string|max:200',
                'state' => 'required|string|max:55',
                'district' => 'required|string|max:55',
                'locality' => 'required|string|max:100',
                'pincode' => 'required|integer',
                'bank' => 'nullable|string|max:100',
                'account_number' => 'nullable|string|max:100',
                'ifsc_code' => 'nullable|string|max:55',
                'branch' => 'nullable|string|max:100',
                'work' => 'nullable|string|max:55',
                'nominee' => 'nullable|string|max:55',
                'relation' => 'nullable|string|max:55',
                'pan_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'aadhar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'aadhar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'bank_doc' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            $customer = User::findOrFail($id);
            $kyc = UserKyc::where('user_id', $id)->first();

            if (!$kyc) {
                $kyc = new UserKyc();
                $kyc->user_id = $id;
            }

            $kyc->name = $request->name;
            $kyc->mobile_no = $request->mobile_no;
            $kyc->pan_no = $request->pan_no;
            $kyc->aadhar_no = $request->aadhar_no;
            $kyc->dob = $request->dob;
            $kyc->gender = $request->gender;
            $kyc->address = $request->address;
            $kyc->state = $request->state;
            $kyc->district = $request->district;
            $kyc->locality = $request->locality;
            $kyc->pincode = $request->pincode;
            $kyc->bank = $request->bank;
            $kyc->account_number = $request->account_number;
            $kyc->ifsc_code = $request->ifsc_code;
            $kyc->branch = $request->branch;
            $kyc->work = $request->work;
            $kyc->nominee = $request->nominee;
            $kyc->relation = $request->relation;

            // Handle file uploads
            if ($request->hasFile('pan_front')) {
                $panFront = $request->file('pan_front');
                $panFrontName = time() . '_pan_front.' . $panFront->getClientOriginalExtension();
                $panFront->move(public_path('kyc/pan'), $panFrontName);
                $kyc->pan_front = 'kyc/pan/' . $panFrontName;
            }

            if ($request->hasFile('aadhar_front')) {
                $aadharFront = $request->file('aadhar_front');
                $aadharFrontName = time() . '_aadhar_front.' . $aadharFront->getClientOriginalExtension();
                $aadharFront->move(public_path('kyc/aadhar'), $aadharFrontName);
                $kyc->aadhar_front = 'kyc/aadhar/' . $aadharFrontName;
            }

            if ($request->hasFile('aadhar_back')) {
                $aadharBack = $request->file('aadhar_back');
                $aadharBackName = time() . '_aadhar_back.' . $aadharBack->getClientOriginalExtension();
                $aadharBack->move(public_path('kyc/aadhar'), $aadharBackName);
                $kyc->aadhar_back = 'kyc/aadhar/' . $aadharBackName;
            }

            if ($request->hasFile('bank_doc')) {
                $bankDoc = $request->file('bank_doc');
                $bankDocName = time() . '_bank_doc.' . $bankDoc->getClientOriginalExtension();
                $bankDoc->move(public_path('kyc/bank'), $bankDocName);
                $kyc->bank_doc = 'kyc/bank/' . $bankDocName;
            }

            if ($kyc->save()) {
                return response(['status' => true, 'msg' => 'KYC Information Saved Successfully.']);
            }

            return response(['status' => false, 'msg' => 'Something Went wrong, KYC Not Saved.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function updateKycStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kyc_status' => 'required|string|in:pending,verified,rejected'
            ]);

            if ($validator->fails()) {
                return response(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            $kyc = UserKyc::where('user_id', $id)->first();
            if (!$kyc) {
                return response(['status' => false, 'msg' => 'KYC record not found.']);
            }

            $kyc->status = $request->kyc_status;
            $kyc->verified_at = $request->kyc_status === 'verified' ? now() : null;
            $kyc->verified_by = Auth::id();

            if ($kyc->save()) {
                return response(['status' => true, 'msg' => 'KYC Status Updated Successfully.']);
            }

            return response(['status' => false, 'msg' => 'Something Went wrong, KYC Status Not Updated.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = User::findOrFail($id);
            
            if ($customer->delete()) {
                return response(['status' => true, 'msg' => 'Customer Deleted Successfully.']);
            }

            return response(['status' => false, 'msg' => 'Something Went wrong, Customer Not Deleted.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }
} 