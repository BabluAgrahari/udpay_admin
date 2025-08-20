<?php

use App\Models\Cart;
use App\Models\Merchant;
use App\Models\PayoutTransaction;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Wallet;
use Illuminate\Support\Str;

if (!function_exists('getCookieData')) {
    function getCookieData($cookieName)
    {
        return Cookie::get($cookieName);
    }
}

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


//function for wallet balance
if (!function_exists('walletBalance')) {
    function walletBalance($user_id)
    {
        $wallet = Wallet::where('userid',$user_id)->first();
        return $wallet->amount + $wallet->earning + $wallet->unicash;
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

            while (\DB::connection('mysql')->table($collection)->where($column, $slug)->exists()) {
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
        return UserWallet::where('user_id', Auth::id())->first();
    }
}

/** Wallet Management Functions */
if (!function_exists('userWalletUpdate')) {
    function userWalletUpdate($data)
    {
        $data = (object) $data;
        // Get or create user wallet
        $wallet = UserWallet::where('user_id', $data->user_id)->first();

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
        $history->wallet_id = $wallet->id;
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
                'wallet_id' => $wallet->id,
                'history_id' => $history->id,
                'new_balance' => $newAmount
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to save wallet history',
        ];
    }
}

// check a valid image url
if (!function_exists('isValidImageUrl')) {
    function isValidImageUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
if (!function_exists('viewCart')) {
    function viewCart(Request $request)
    {
        $cartItems = [];

        if ($request->user()) {
            $cartItems = Cart::where('user_id', $request->user()->id)->get();
        } else {
            $cookieId = $request->cookie('cart_cookie_id');
            if ($cookieId) {
                $cartItems = Cart::where('cookie_id', $cookieId)->get();
            }
        }

        return view('cart.index', compact('cartItems'));
    }
}

if (!function_exists('cartCount')) {
    function cartCount()
    {
        $cartCount = 0;

        if (Auth::user()) {
            $cartCount = Cart::where('user_id', Auth::user()->id)->sum('quantity');
        } else {
            $cookieId = Cookie::get('cart_cookie_id');
            if ($cookieId) {
                $cartCount = Cart::where('cart_cookie_id', $cookieId)->sum('quantity');
            }
        }
        return (int) $cartCount;
    }
}

if (!function_exists('total_cart_amount')) {
    function total_cart_amount()
    {
        $totalAmount = 0;
        if (auth()->user()) {
            $userId = auth()->user()->id;
            $totalAmount = DB::table('uni_cart')
                ->join('uni_products', 'uni_cart.product_id', '=', 'uni_products.id')
                ->where('uni_cart.user_id', $userId)
                ->sum(DB::raw('uni_products.price * uni_cart.quantity'));
        } else {
            $cookieId = Cookie::get('cart_cookie_id');  // $request->cookie('');
            if ($cookieId) {
                $totalAmount = DB::table('uni_cart')
                    ->join('uni_products', 'uni_cart.product_id', '=', 'uni_products.id')
                    ->where('uni_cart.cart_cookie_id', $cookieId)
                    ->sum(DB::raw('uni_products.price * uni_cart.quantity'));
            }
        }

        return $totalAmount;
    }
}

// Get image with fallback to no-image placeholder
if (!function_exists('getImageWithFallback')) {
    function getImageWithFallback($imageUrl, $placeholder = null)
    {
        // If no image URL provided, return placeholder
        if (empty($imageUrl)) {
            return $placeholder ?? asset('front_assets/images/no-image.png');
        }

        // If it's a valid URL, return it (we'll handle 404s with CSS/JS)
        if (filter_var($imageUrl, FILTER_VALIDATE_URL) !== false) {
            return $imageUrl;
        }

        // If it's a relative path, check if file exists
        if (file_exists(public_path($imageUrl))) {
            return asset($imageUrl);
        }

        // Return placeholder if image doesn't exist
        return $placeholder ?? asset('front_assets/images/no-image.svg');
    }
}

if (!function_exists('insertPayout')) {
    function insertPayout($amount, $parent_id, $in_type, $user_id, $order_id, $sv, $level)
    {
        $qry = new Payout();
        $qry->amount = $amount;
        $qry->parent_id = $parent_id;
        $qry->user_id = $user_id;
        $qry->in_type = $in_type;
        $qry->cur_date = date('Y-m-d');
        $qry->sv = $sv;
        $qry->level = $level;
        $qry->order_id = $order_id;
        $qry->status = 1;
        if ($qry->save())
            return true;

        return false;
    }
}

if (!function_exists('addWallet1')) {
    function addWallet1($key, $uid, $payoutVal, $order_id, $tp)
    {
        $payout = $payoutVal - ($payoutVal * 0.05);
        $walletBal = Wallet::where('unm', $uid)->first();
        if (empty($walletBal))
            return false;
        if ($key == 1) {
            $walletBal->earning = $walletBal->earning + $payout;
            $walletBal->save();
        }
        $tds = $payoutVal * 0.05;

        $save1 = new WalletTransition();
        $save1->unm = $uid;
        $save1->user_id = $walletBal->userid;
        $save1->credit = $payout;
        $save1->balance = $walletBal->amount + $walletBal->earning + $walletBal->unicash;
        $save1->transition_type = $tp;
        $save1->in_type = 'Your Wallet is Creditd ' . $payout . ' as Performance Bonus from Unipay';
        $save1->ord_id = $order_id;
        $save1->unicash = 0;
        $save1->earning = $payout;
        $save1->amount = 0;
        $save1->unipoint = 0;
        $save1->created_on = date('Y-m-d H:i:s');
        $save1->description = '';
        $save1->save();

        $insert = new PayoutTransaction();
        $insert->uid = $walletBal->userid;
        $insert->amount = $payoutVal;
        $insert->tds_amt = $tds;
        $insert->net_amt = $payout;
        $insert->cur_date = date('Y-m-d');
        $insert->for_date = date('Y-m-d');
        $insert->status = 1;
        $insert->pay_type = '';
        $insert->st = 0;
        if ($insert->save())
            return true;

        return false;
    }

    if (!function_exists('insertPayoutSelf')) {
        function insertPayoutSelf($amt, $parent, $in_type, $child, $order_id, $sv, $level, $tp)
        {
            $qry = new Payout();
            $qry->amount = $amt;
            $qry->parent_id = $parent;
            $qry->user_id = $child;
            $qry->in_type = $in_type;
            $qry->cur_date = date('Y-m-d');
            $qry->status = 1;
            $qry->sv = $sv;
            $qry->level = $level;
            $qry->payout_type = $tp;
            $qry->order_id = $order_id;
            if ($qry->save())
                return true;

            return false;
        }
    }


    if (!function_exists('walletTransaction')) {
        function walletTransaction($request)
        {
            
            $request = (object)$request;
            $userData = User::where('user_num',$request->unm)->first();
            $save = new WalletTransition();
            $save->unm	   = $request->unm;
            $save->user_id     = $request->user_id;
            $save->transition_type  = $request->transition_type ?? '';
            $save->credit      = $request->credit ?? 0;
            $save->debit       = $request->debit ?? 0;
            $save->balance      = $request->balance ?? 0;
            $save->in_type     = $request->in_type ?? '';
            $save->created_on  = date('Y-m-d H:i:s');
            $save->description = $request->description ?? '';
            $save->remark      = $request->remark ?? '';
            $save->unicash     = $request->unicash ?? 0;
            $save->earning     = $request->earning ?? 0;
            $save->amount      = $request->amount ?? 0;
            $save->unipoint    = $request->unipoint ?? 0;
            $save->ord_id      = $request->order_id ?? '';
            if ($save->save())
                return true;
    
            return false;
        }
    }
}
