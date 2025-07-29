<?php
//use Log;
namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product\Products;
use App\Models\Product\RpTransition;
use App\Models\Product\ApTransition;
use App\Models\Product\Cart;
use App\Models\Product\ApRepurchaseOrder;
use App\Models\Product\RepurchaseOrder;
use App\Models\Deals\DealOrders;
//use DB;
use App\Models\Product\OfferPayout;
use App\Models\Uni_cash_detail;

use Exception;

class GatewayRazorController extends Controller
{

	public function index(Request $request){
		try{
			
			return ['status' => false, 'msg'=>'our Gateway Services will undergo a temporary shutdown'];
			
			$uid = $request->userId;
			
			$user = User::where('user_id',$uid)->first();
			
			$ord = $request->transaction_id;
			$transID = $this->generateOrderId();
			$paymentmode = $request->paymentmode;
			
			
			$save = new Uni_cash_detail();
			
			$save->user_id = $uid;
			$save->our_ord_id = $transID;
			$save->unm = $user->user_nm;
			$save->amount = $request->amount;
			$save->order_id = $ord;
			$save->transition_id = 0;
			$save->status		 = 'Initiate';
			$save->payout_type   = $paymentmode;
			$save->bank_txn_id	 = '';
			$save->bank_res_code = 0;
			$save->description	 = '';
			$save->flag			 = 0;
			$save->save();
				
			if($save){
				return ['status' => true, 'msg'=>'success','order_id'=>$transID];
			}else{
				return ['status' => false, 'msg'=>'error'];
			}
			
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function generateOrderId1()
	{
		return  $timestamp = now()->format('ymdHis'); 
	}
	
	
	function generateOrderId()
	{
		$prefix = 'UNI';
		$uniquePart = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 13));
		//$suffix = $suffix ?? rand(1000, 9999); // fallback to random 4-digit number

		return "{$prefix}{$uniquePart}";
	}
	public function generateOrderCode(): string
	{
		$timestamp = now()->format('ymdHis'); // e.g., 250712153045
		$rand = strtoupper(Str::random(4));   // e.g., AB9X
		return $rand . substr($timestamp, -8); // Final: AB9X153045
		
	}
	
}