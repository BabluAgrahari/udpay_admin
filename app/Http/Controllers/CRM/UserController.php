<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $perPage = $request->perPage ?? config('global.perPage');
            $data['records'] = User::where('role','!=', 'supperadmin')->paginate($perPage);

            return view('CRM.User.index', $data);
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }


    public function create()
    {
        try {
            return view('CRM.User.create');
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $save = new User();
            $save->name = $request->name;
            $save->email = $request->email;
            $save->password = bcrypt($request->password);
            $save->role = 'admin'; // Default role for new user
            $save->mobile_no = $request->mobile_no;
            $save->gender = $request->gender;
            $save->city = $request->city;
            $save->country = $request->country;
            $save->state = $request->state;
            $save->address = $request->address;
            $save->pincode = $request->pincode;
            $save->status = (int) $request->status;
            if ($save->save())
                return response(['status' => true, 'msg' => 'User Created Successfully.']);

            return response(['status' => false, 'msg' => 'Something Went wrong, User Not Created.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $record = User::find($id);
            return response(['status' => true, 'record' => $record]);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {

            $save = User::find($request->id);
            if (!$save) {
                return response(['status' => false, 'msg' => 'User Not Found.']);
            }
            $save->name = $request->name;
            $save->mobile_no = $request->mobile_no;
            $save->gender = $request->gender;
            $save->city = $request->city;
            $save->country = $request->country;
            $save->state = $request->state;
            $save->address = $request->address;
            $save->pincode = $request->pincode;
            $save->status = (int) $request->status;
            if ($save->save())
                return response(['status' => true, 'msg' => 'User Updated Successfully.']);

            return response(['status' => false, 'msg' => 'Something Went wrong, User Not updated.']);
        } catch (Exception $e) {
            return response(['status' => false, 'msg' => $e->getMessage()]);
        }
    }


}
