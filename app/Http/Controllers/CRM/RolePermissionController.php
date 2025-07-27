<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::all();
            return view('CRM.RolePermission.index', compact('roles'));
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $existingRole = Role::where('role', strtolower($request->role))->first();
            if($existingRole)
                return $this->failMsg('Your can not create role because it already exists.');

        $role = new Role();
        $role->role = strtolower($request->role);
        $role->permissions = $request->permissions ?? [];
        if($role->save())
            return $this->successMsg('Role created successfully.');
        
            return $this->failMsg('Role not created.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {   
            $role = Role::find($id);
            if (!$role) {
                return $this->failMsg('Role not found.');
            }
            return $this->successMsg('Role found.', $role);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $role = Role::find($id);
            if (!$role) 
                return $this->failMsg('Role not found.');

            $existingRole = Role::where('role', strtolower($request->role))->where('_id', '!=', $id)->first();
            if($existingRole)
                return $this->failMsg('Your can not update role because it already exists.');
        
            $role->role = strtolower($request->role);
            $role->permissions = $request->permissions ?? [];
            if($role->save())
                return $this->successMsg('Role updated successfully.');
            
            return $this->failMsg('Role not updated.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function saveModulePermission(Request $request)
    {
        try {
            $role = Role::find($request->role_id);
            if (!$role) 
                return $this->failMsg('Role not found.');

            $role->permissions = $request->permissions ?? [];
            if($role->save())
                return $this->successMsg('Module permission saved successfully.');

            return $this->failMsg('Module permission not saved.');
            
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
} 