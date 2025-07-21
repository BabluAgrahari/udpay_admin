<?php

use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use MongoDB\BSON\ObjectId;

if (!function_exists('uniqCode')) {
    function uniqCode($lenght)
    {
        // uniqCode
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception('no cryptographically secure random function available');
        }
        return strtoupper(substr(bin2hex($bytes), 0, $lenght));
    }
}

if (!function_exists('singleFile')) {
    function singleFile($file, $folder)
    {
        $folder = strtolower($folder);
        if ($file) {
            if (!file_exists($folder))
                mkdir($folder, 0777, true);

            $destinationPath = public_path() . '/' . $folder;
            $profileImage = date('YmdHis') . rand(111, 999) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $profileImage);
            $fileName = "$profileImage";

            return asset($folder) . '/' . $fileName;
        }
        return false;
    }
}

if (!function_exists('multiFile')) {
    function multiFile($files, $folder)
    {
        $fileNames = [];
        foreach ($files as $key => $file) {
            if ($file) {
                if (!file_exists($folder))
                    mkdir($folder, 0777, true);

                $filename = date('YmdHis') . rand(111, 999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/' . $folder, $filename);
                $fileNames[$key] = asset($folder) . '/' . $filename;  // $filename;
            }
        }
        return $fileNames;
    }
}

if (!function_exists('pr')) {
    function pr($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }
}

function ip_address()
{
    return !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
}

function status($stauts)
{
    if ($stauts) {
        return '<span class="badge bg-label-success">Active</span>';
    } else {
        return '<span class="badge bg-label-danger">Inactive</span>';
    }
}

function daterange($data)
{
    if (!empty($data['date_range'])) {
        $dateRange = $data['date_range'];
        $date = explode('-', $dateRange);
        list($start_date, $end_date) = $date;
        $start_date = date('m/d/Y', strtotime(trim($start_date)));
        $end_date = date('m/d/Y', strtotime(trim($end_date)));
    } else {
        // strtotime('-29 days',
        $crrMonth = date('2023-01-01');  // (date('Y-m-d'));
        $start_date = (trim(date('m/d/Y', strtotime($crrMonth))));
        $end_date = (trim(date('m/d/Y')));
    }

    return '
    <input type="text" name="date_range" id="daterange-btn" class=" form-control" value="' . $start_date . ' - ' . $end_date . '" />';
}

function user()
{
    $user = User::where('role', 'supperadmin')->first();
    return $user ?? [];
}

function mSign($amount)
{
    $amount = number_format($amount, 2);
    return '₹ ' . $amount;
}

function getPerValue($_per, $amount)
{
    return ($_per / 100) * $amount;
}

if (!function_exists('generateSlug')) {
    function generateSlug($name, $collection = null, $column = 'slug')
    {
        $slug = Str::slug($name);

        if ($collection) {
            $originalSlug = $slug;
            $count = 1;

            while (\DB::connection('mongodb')->collection($collection)->where($column, $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        }

        return $slug;
    }
}

/** Stock Management Functions */
if (!function_exists('stockUpdate')) {
    function stockUpdate($data)
    {
        $data = (object) $data;

        $query = Stock::where('product_id', $data->product_id);

        if (!empty($data->product_variant_id)) {
            $query->where('product_variant_id', $data->product_variant_id);
        }
        $stockList = $query->first();

        // Get current closing stock (default to 0 if no record exists)
        $currentClosingStock = $stockList ? $stockList->closing_stock : 0;

        if ($data->type == 'up') {
            $closingStock = $currentClosingStock + $data->stock;
        } else if ($data->type == 'down') {
            $closingStock = $currentClosingStock - $data->stock;
        } else {
            $closingStock = $currentClosingStock;
        }

        $stockLog = new Stock();
        $stockLog->product_id = $data->product_id;
        $stockLog->product_variant_id = $data->product_variant_id ?? null;
        $stockLog->stock = $data->stock;
        $stockLog->unit_id = $data->unit_id ?? null;
        $stockLog->type = $data->type;
        $stockLog->remarks = $data->remarks ?? null;
        $stockLog->user_id = $data->user_id;
        $stockLog->order_id = $data->order_id ?? null;
        $stockLog->closing_stock = $closingStock;
        $stockLog->source = $data->source ?? 'system';
        if ($stockLog->save()) {
            return [
                'success' => true,
                'message' => 'Stock updated successfully',
            ];
        }
        return [
            'success' => false,
            'message' => 'Stock not increased',
        ];
    }
}

if (!function_exists('wallet')) {
    function wallet()
    {
        return UserWallet::where('user_id', new ObjectId(Auth::id()))->first();
    }
}

/** Wallet Management Functions */
if (!function_exists('userWalletUpdate')) {
    function userWalletUpdate($data)
    {
        $data = (object) $data;
        // Get or create user wallet
        $wallet = UserWallet::where('user_id', new ObjectId($data->user_id))->first();

        if (!$wallet) {
            $wallet = new UserWallet();
            $wallet->user_id = $data->user_id;
            $wallet->available_amount = 0;
            $wallet->added_by = $data->action_by;
        }

        $currentAmount = $wallet->available_amount;

        // Calculate new balance
        if ($data->type == 'credit') {
            $newAmount = $currentAmount + $data->amount;
        } else {
            // Check if user has sufficient balance for debit
            if ($currentAmount < $data->amount) {
                return [
                    'success' => false,
                    'message' => 'Insufficient wallet balance for debit transaction.',
                ];
            }
            $newAmount = $currentAmount - $data->amount;
        }

        // Update wallet balance
        $wallet->available_amount = $newAmount;
        if (!$wallet->save()) {
            return [
                'success' => false,
                'message' => 'Failed to update wallet balance.',
            ];
        }

        // Store wallet history
        $history = new WalletHistory();
        $history->user_id = $data->user_id;
        $history->wallet_id = $wallet->_id;
        $history->type = $data->type;
        $history->remarks = $data->remarks ?? null;
        $history->amount = $data->amount;
        $history->closing_amount = $newAmount;
        $history->source = $data->source ?? 'system';
        $history->action_by = $data->action_by;

        if ($history->save()) {
            return [
                'success' => true,
                'message' => 'Wallet transaction completed successfully',
                'wallet_id' => $wallet->_id,
                'history_id' => $history->_id,
                'new_balance' => $newAmount
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to save wallet history',
        ];
    }
}

// if (!function_exists('walletCredit')) {
//     function walletCredit($userId, $amount, $remarks = null, $source = 'system', $actionBy = null)
//     {
//         $data = [
//             'user_id' => $userId,
//             'type' => 'credit',
//             'amount' => $amount,
//             'remarks' => $remarks,
//             'source' => $source,
//             'action_by' => $actionBy ?? Auth::id()
//         ];

//         return walletHistoryStore($data);
//     }
// }

// if (!function_exists('walletDebit')) {
//     function walletDebit($userId, $amount, $remarks = null, $source = 'system', $actionBy = null)
//     {
//         $data = [
//             'user_id' => $userId,
//             'type' => 'debit',
//             'amount' => $amount,
//             'remarks' => $remarks,
//             'source' => $source,
//             'action_by' => $actionBy ?? Auth::id()
//         ];

//         return walletHistoryStore($data);
//     }
// }
