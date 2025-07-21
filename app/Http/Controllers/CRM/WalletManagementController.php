<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletManagementRequest;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletHistory;
use App\Traits\WebResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;
use Exception;

class WalletManagementController extends Controller
{
    use WebResponse;

    public function index()
    {
        try {
            $admins = User::where('role', 'admin')->get();
            return view('CRM.WalletManagement.admin', compact('admins'));
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function getUserWallet(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string|exists:users,user_id'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $user = User::where('user_id', $request->user_id)->first();
            $userId = $user->_id;
            $wallet = UserWallet::where('user_id', new ObjectId($userId))->first();

            return $this->successRes('Wallet balance retrieved successfully', [
                'available_amount' => $wallet ? $wallet->available_amount : 0
            ]);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string|exists:users,_id',
                'amount' => 'required|numeric|min:0.01',
                'type' => 'required|in:credit,debit',
                'remarks' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $payload = [
                'action_by' => Auth::id(),
                'user_id' => new ObjectId($request->user_id),
                'type' => $request->type,
                'amount' => $request->amount,
                'remarks' => $request->remarks,
                'source' => $request->type == 'credit' ? 'credit by superadmin' : 'debit by superadmin'
            ];

            $result = userWalletUpdate($payload);
            if (!$result['success']) {
                return $this->failMsg($result['message']);
            }

            return $this->successMsg('Wallet transaction completed successfully');
        } catch (Exception $e) {
            return $this->failMsg('Something went wrong. Please try again.');
        }
    }

    public function history(Request $request)
    {
        try {
            
            $query = User::query();
            if (Auth::user()->role == 'supperadmin') {
                $query->where('role', 'admin');
            } else if (Auth::user()->role == 'admin') {
                $query->where('role', 'customer');
            }
            $users = $query->get();

            return view('CRM.WalletManagement.history', compact('users'));
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function getTransactionDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'transaction_id' => 'required|string|exists:wallet_history,_id'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $transaction = WalletHistory::with(['user', 'actionBy', 'wallet'])
                ->find($request->transaction_id);

            if (!$transaction) {
                return $this->failMsg('Transaction not found');
            }

            return $this->successRes('Transaction details retrieved successfully', [
                'transaction' => $transaction,
                'user_name' => $transaction->user ? $transaction->user->first_name . ' ' . $transaction->user->last_name : 'N/A',
                'action_by' => $transaction->actionBy ? $transaction->actionBy->first_name . ' ' . $transaction->actionBy->last_name : 'N/A',
                'amount_formatted' => mSign($transaction->amount),
                'closing_amount_formatted' => mSign($transaction->closing_amount),
                'created_at' => $transaction->created ? $transaction->dFormat($transaction->created) : 'N/A'
            ]);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function userTransferIndex()
    {
        try {
            return view('CRM.WalletManagement.userTransfer');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function getUserDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $user = User::where('user_id', $request->user_id)->first();

            if (!$user) {
                return $this->failMsg('User not found');
            }

            return $this->successRes('User details retrieved successfully', $user);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function getAdminWallet(Request $request)
    {
        try {
            $adminId = $request->user_id;
            $wallet = UserWallet::where('user_id', new ObjectId($adminId))->first();

            return $this->successRes('Admin wallet balance retrieved successfully', [
                'available_amount' => $wallet ? $wallet->available_amount : 0
            ]);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function userTransfer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string|exists:users,user_id',
                'amount' => 'required|numeric|min:0.01',
                'remarks' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $user = User::where('user_id', $request->user_id)->first();
            if (!$user) {
                return $this->failMsg('User not found');
            }

            $adminId = Auth::id();
            $userId = $user->_id;
            $amount = $request->amount;
            $remarks = $request->remarks;

            // Debit from admin wallet
            $adminPayload = [
                'action_by' => $adminId,
                'user_id' => new ObjectId($adminId),
                'type' => 'debit',
                'amount' => $amount,
                'remarks' => $remarks ? 'Transfer to user: ' . $remarks : 'Transfer to user',
                'source' => 'user_transfer'
            ];

            $adminResult = userWalletUpdate($adminPayload);
            if (!$adminResult['success']) {
                return $this->failMsg($adminResult['message']);
            }

            // Credit to user wallet
            $userPayload = [
                'action_by' => $adminId,
                'user_id' => new ObjectId($userId),
                'type' => 'credit',
                'amount' => $amount,
                'remarks' => $remarks ? 'Received from admin: ' . $remarks : 'Received from admin',
                'source' => 'user_transfer'
            ];

            $userResult = userWalletUpdate($userPayload);
            if (!$userResult['success']) {
                return $this->failMsg($userResult['message']);
            }

            return $this->successMsg('Amount transferred to user successfully');
        } catch (Exception $e) {
            return $this->failMsg('Something went wrong. Please try again.');
        }
    }

    public function userToUserTransferIndex()
    {
        try {
            return view('CRM.WalletManagement.userToUserTransfer');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function userToUserTransfer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sender_user_id' => 'required|string|exists:users,user_id',
                'receiver_user_id' => 'required|string|exists:users,user_id',
                'amount' => 'required|numeric|min:0.01',
                'remarks' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            // Check if sender and receiver are different
            if ($request->sender_user_id === $request->receiver_user_id) {
                return $this->failMsg('Sender and receiver cannot be the same user');
            }

            // Get sender and receiver users
            $senderUser = User::where('user_id', $request->sender_user_id)->where('role','customer')->first();
            $receiverUser = User::where('user_id', $request->receiver_user_id)->where('role','customer')->first();

            if (!$senderUser) {
                return $this->failMsg('Sender user not found');
            }

            if (!$receiverUser) {
                return $this->failMsg('Receiver user not found');
            }

            $senderId = $senderUser->_id;
            $receiverId = $receiverUser->_id;
            $amount = $request->amount;
            $remarks = $request->remarks;
            $actionBy = Auth::id();

            // Debit from sender wallet
            $senderPayload = [
                'action_by' => $actionBy,
                'user_id' => new ObjectId($senderId),
                'type' => 'debit',
                'amount' => $amount,
                'remarks' => $remarks ? 'Transfer to ' . $receiverUser->first_name . ': ' . $remarks : 'Transfer to ' . $receiverUser->first_name,
                'source' => 'user_to_user_transfer'
            ];

            $senderResult = userWalletUpdate($senderPayload);
            if (!$senderResult['success']) {
                return $this->failMsg($senderResult['message']);
            }

            // Credit to receiver wallet
            $receiverPayload = [
                'action_by' => $actionBy,
                'user_id' => new ObjectId($receiverId),
                'type' => 'credit',
                'amount' => $amount,
                'remarks' => $remarks ? 'Received from ' . $senderUser->first_name . ': ' . $remarks : 'Received from ' . $senderUser->first_name,
                'source' => 'user_to_user_transfer'
            ];

            $receiverResult = userWalletUpdate($receiverPayload);
            if (!$receiverResult['success']) {
                return $this->failMsg($receiverResult['message']);
            }

            return $this->successMsg('Amount transferred between users successfully');
        } catch (Exception $e) {
            return $this->failMsg('Something went wrong. Please try again.');
        }
    }

    public function datatable(Request $request)
    {
        $query = WalletHistory::with(['user', 'actionBy'])->access();

        // Filtering
        if ($request->filled('user_id')) {
            $query->where('user_id', new ObjectId($request->user_id));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_range')) {
            $dateRange = $request->date_range;
            $dates = explode('-', $dateRange);
            $startDate = trim($dates[0]);
            $endDate = trim($dates[1]);
            $query->whereBetween('created', [
                date('Y-m-d 00:00:00', strtotime($startDate)),
                date('Y-m-d 23:59:59', strtotime($endDate))
            ]);
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
                    if ($colName === 'date') {
                        $query->orderBy('created', $dir);
                    } else {
                        $query->orderBy($colName, $dir);
                    }
                }
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $transactions = $query->skip($start)->take($length)->get();

        $data = $transactions->map(function ($transaction, $key) use ($start) {
            $user = $transaction->user;
            $actionBy = $transaction->actionBy;
            return [
                'index' => $start + $key + 1,
                '_id' => $transaction->_id,
                'user' => $user ? ($user->first_name . ' ' . $user->last_name . '<br><small class="text-muted">' . $user->email . '</small>') : '<span class="text-muted">User not found</span>',
                'type' => $transaction->type == 'credit' ? '<span class="badge bg-label-success">Credit</span>' : '<span class="badge bg-label-danger">Debit</span>',
                'amount' => '<strong>' . mSign($transaction->amount) . '</strong>',
                'closing_amount' => mSign($transaction->closing_amount),
                'source' => match($transaction->source) {
                    'admin_transfer' => '<span class="badge bg-label-primary">Admin Transfer</span>',
                    'user_transfer' => '<span class="badge bg-label-warning">User Transfer</span>',
                    'user_to_user_transfer' => '<span class="badge bg-label-secondary">User to User Transfer</span>',
                    'system' => '<span class="badge bg-label-info">System</span>',
                    default => '<span class="badge bg-label-info">' . ucwords(str_replace('_', ' ', $transaction->source)) . '</span>',
                },
                'action_by' => $actionBy ? ($actionBy->first_name . ' ' . $actionBy->last_name) : '<span class="text-muted">System</span>',
                'date' => $transaction->created ? $transaction->dFormat($transaction->created) : 'N/A',
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
