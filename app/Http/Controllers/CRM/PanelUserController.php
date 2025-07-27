<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\PanelUserRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PanelUserController extends Controller
{
    /**
     * Display a listing of panel users
     */
    public function index(Request $request)
    {
        try {
            $data['roles'] = Role::all();
            return view('CRM.PanelUser.index', $data);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new panel user
     */
    public function create()
    {
        try {
            $data['roles'] = Role::all();
            return view('CRM.PanelUser.create', $data);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    /**
     * Store a newly created panel user
     */
    public function store(PanelUserRequest $request)
    {
        try {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role_id;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->zip_code = $request->zip_code;
            $user->isactive = 1;
            $user->created_by = Auth::user()->_id;

            // Handle profile picture upload using singleFile helper
            if ($request->hasFile('profile_pic')) {
                $profilePicPath = singleFile($request->file('profile_pic'), 'panel-users');
                if ($profilePicPath) {
                    $user->profile_pic = $profilePicPath;
                }
            }

            if ($user->save()) {
                return $this->successMsg('Panel User Created Successfully.');
            }

            return $this->failMsg('Something Went wrong, Panel User Not Created.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    /**
     * Display the specified panel user
     */
    public function show($id)
    {
        try {
            $user = User::whereIn('role', ['admin', 'vendor'])->findOrFail($id);
            return view('CRM.PanelUser.show', compact('user'));
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified panel user
     */
    public function edit($id)
    {
        try {
            $data['user'] = User::whereIn('role', ['admin', 'vendor'])->findOrFail($id);
            $data['roles'] = Role::all();
            return view('CRM.PanelUser.edit', $data);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    /**
     * Update the specified panel user
     */
    public function update(PanelUserRequest $request, $id)
    {
        try {
            $user = User::whereIn('role', ['admin', 'vendor'])->findOrFail($id);
            
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->role_id = $request->role_id;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->zip_code = $request->zip_code;

            // Handle profile picture upload using singleFile helper
            if ($request->hasFile('profile_pic')) {
                // Delete old profile picture if exists
                if ($user->profile_pic && file_exists(public_path($user->profile_pic))) {
                    unlink(public_path($user->profile_pic));
                }
                
                $profilePicPath = singleFile($request->file('profile_pic'), 'panel-users');
                if ($profilePicPath) {
                    $user->profile_pic = $profilePicPath;
                }
            }

            if ($user->save()) {
                return $this->successMsg('Panel User Updated Successfully.');
            }

            return $this->failMsg('Something Went wrong, Panel User Not Updated.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    /**
     * Show the change password form
     */
    public function changePassword($id)
    {
        try {
            $user = User::whereIn('role', ['admin', 'vendor'])->findOrFail($id);
            return view('CRM.PanelUser.changePassword', compact('user'));
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    /**
     * Update the password for the specified panel user
     */
    public function updatePassword(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6|max:255|confirmed',
                'password_confirmation' => 'required|string|min:6|max:255',
            ], [
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 6 characters.',
                'password.max' => 'Password cannot exceed 255 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password_confirmation.required' => 'Password confirmation is required.',
            ]);

            if ($validator->fails()) {
                return response(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            $user = User::whereIn('role', ['admin', 'vendor'])->findOrFail($id);
            $user->password = Hash::make($request->password);

            if ($user->save()) {
                return $this->successMsg('Password Changed Successfully.');
            }

            return $this->failMsg('Something Went wrong, Password Not Changed.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    /**
     * Remove the specified panel user
     */
    public function destroy($id)
    {
        try {
            $user = User::whereIn('role', ['admin', 'vendor'])->findOrFail($id);
            
            // Delete profile picture if exists
            if ($user->profile_pic && file_exists(public_path($user->profile_pic))) {
                unlink(public_path($user->profile_pic));
            }
            
            if ($user->delete()) {
                return $this->successMsg('Panel User Deleted Successfully.');
            }

            return $this->failMsg('Something Went wrong, Panel User Not Deleted.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    /**
     * Update status of panel user
     */
    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:users,_id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $user = User::whereIn('role', ['admin', 'vendor'])->findOrFail($request->id);
            $user->isactive = (int) $request->status;

            if ($user->save()) {
                $statusText = $request->status ? 'activated' : 'deactivated';
                return $this->successMsg("Panel User {$statusText} successfully.");
            }

            return $this->failMsg('Something Went wrong, Status Not Updated.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $query = User::with('roleTable')->whereNotIn('role', ['supperadmin', 'customer']);

        // Filtering
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (!empty($request->role)) {
            $query->where('role_id', $request->role);
        }
        if ($request->status || $request->status == 0 && $request->status !== null) {
            $query->where('isactive', (int)$request->status);
        }

        $total = $query->count();

        // Ordering
        $columns = $request->columns;
        if ($request->order && count($request->order)) {
            foreach ($request->order as $order) {
                $colIdx = $order['column'];
                $colName = $columns[$colIdx]['data'];
                $dir = $order['dir'];
                if ($colName !== 'index' && $colName !== 'actions') {
                    $query->orderBy($colName, $dir);
                }
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $users = $query->skip($start)->take($length)->get();

        $data = $users->map(function ($user, $key) use ($start) {
            $profile = $user->profile_pic ? '<img src="' . asset($user->profile_pic) . '" alt="Profile" class="rounded-circle" width="40" height="40">'
                : '<img src="' . asset('assets/img/avatars/1.png') . '" alt="Default Profile" class="rounded-circle" width="40" height="40">';
            $address = $user->address ? ($user->address . ', ' . $user->city . ', ' . $user->state . ' ' . $user->zip_code) : '<span class="text-muted">No address</span>';
            $statusSwitch = '<div class="form-check form-switch">'
                . '<input class="form-check-input status-switch" type="checkbox" data-id="' . $user->_id . '" ' . ($user->isactive ? 'checked' : '') . '>'
                . '</div>';
            return [
                'index' => $start + $key + 1,
                '_id' => $user->_id,
                'profile' => $profile,
                'name' => '<strong>' . $user->first_name . ' ' . $user->last_name . '</strong>',
                'email' => $user->email,
                'role' => '<span class="badge bg-label-info">' . ucwords(str_replace('_', ' ', $user->roleTable->role ?? '')) . '</span>',
                'address' => $address,
                'status' => $statusSwitch,
                'created_at' => $user->dFormat($user->created),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
} 