<?php

namespace App\Http\Controllers\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Register;
use App\Services\SMSSender;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransition;


class UpinController extends Controller
{
    public function get_epin_otp(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'userId' => 'required|numeric',
        ]);
  
        if($validator->fails())
            return self::validationRes($validator->messages());
       
        $uid = $request->userId;
        $num_row = User::where('user_id',$uid)->where('email',$request->email)->first();
		
        if(!empty($num_row)){
            if($num_row->mobile == ''){
                return $this->failRes('Mobile number not available....'); 
            }else{
                $otp = rand(111111, 999999);
                $sms = new SMSSender;
                $send = $sms->sendMessage('send_otp',$num_row->mobile,$otp,'','');
                if($send){
                    $mobs = '+91********'.substr($num_row->mobile, strlen($num_row->mobile)-2);
                    $save = User::find($num_row->id);
                    $save->mobile_otp    = md5($otp);
                    $save->expire_otp_at = strtotime(Carbon::now()->addMinutes(60));
                    if($save->save())
                        return $this->successRes('OTP Sent on +91********' . substr($num_row->mobile, -2));
                    
                    return $this->failRes('OTP not Sent....');
                          
                    
                }else{
                    return $this->failRes('OTP not Sent....');
                }   
            }
        }else{
            return $this->failRes('User not found....');
           
        }
        
    }

    public function verify_epin_otp(Request $request){
        
        $validator = Validator::make($request->all(),[
            'userId' =>'required|numeric',
            'otp'=>'required|numeric',
        ]);
        if($validator->fails())
            return self::validationRes($validator->messages()); 
        
        $uid = $request->userId;
		$num_row = User::where('user_id',$uid)->first();
        if($num_row){
            if(strtotime(now()) >= $num_row->expire_otp_at){  
                return $this->failRes('OTP Expired.....'); 
            }else{
                
                if(md5($request->otp) == $num_row->mobile_otp){
                    return $this->successRes('OTP Verified Successfully....');
                        
                }else{
                    return $this->failRes('OTP Missmatch Please Enter Correct OTP....');
                    
                }
            }
		}else{
            return $this->failRes('No Record Found.....');
		}		
	}

    public function generate_epin(Request $request){
        $validator = Validator::make($request->all(),[
            'userId' =>'required',
            'otp'=>'required|numeric|digits:6',
            'upin'=>'required|numeric',
        ]);
        if($validator->fails())
            return self::validationRes($validator->messages()); 

        $uid = $request->userId;
        $num_row = User::where('user_id',$uid)->first();
        if($num_row){
            if(md5($request->otp) == $num_row->mobile_otp){
                $save =User::find($num_row->id);
                $save->epin = md5($request->upin);
                if($save->save())
                    return $this->successRes('U-pin Generated Successfully....'); 
                
                return $this->failRes('Somthing Went Wrong....');
                
            }else{
                return $this->failRes('OTP Missmatch Please Enter Correct OTP....');
                
            }
        }else{
            return $this->failRes('No Record Found.....');
        }
       
    }

    public function reset_epin_plan(Request $request)
{
    // Validate inputs
    $validator = Validator::make($request->all(), [
        'uplan'   => 'required|numeric',
        'userId'  => 'required|numeric',
        'otp'     => 'required|numeric|digits:6',
    ]);

    if ($validator->fails()) {
        return $this->validationRes($validator->messages());
    }

    $uid = $request->userId;
    $user = User::where('user_id', $uid)->first();

    if (!$user) {
        return $this->failRes('User not found.');
    }

    $wallet = Wallet::where('userid', $uid)->first();

    if (!$wallet) {
        return $this->failRes('Wallet not found.');
    }

    // Check sufficient balance
    if (($wallet->amount + $wallet->unicash + $wallet->earning) < 1) {
        return $this->failRes('Insufficient Balance.');
    }

    // Generate unique transaction ID
    do {
        $transID = rand(111111, 999999);
    } while (WalletTransition::where('ord_id', $transID)->exists());

    // If user already has an epin, check wallet and allow update
    if (!empty($user->epin) && $user->epin != 0) {
        $check_wallet = self::check_wallet($amt = 1, $uid, $transID, $user->user_nm);

        if ($check_wallet['msg'] == 'true') {
            $user->epin = md5($request->uplan);
            if ($user->save()) {
                return $this->successRes('U-pin Reset Successfully: ' . $user->epin);
            } else {
                return $this->failRes('Something went wrong while saving.');
            }
        } else {
            return $this->failRes('Wallet deduction failed.');
        }
    }else{
		 $user->epin = md5($request->uplan);
            if ($user->save()) {
                return $this->successRes('U-pin Reset Successfully: ' . $user->epin);
            } else {
                return $this->failRes('Something went wrong while saving.');
            }
	}

    // If no existing epin, generate new one
    return self::generate_epin($request);
}

    function get_reset_epin_otp(Request $request){
       return self::get_epin_otp($request);
    }

    function verify_reset_epin_otp(Request $request){
        return self::verify_epin_otp($request);
     }

    public function check_wallet($amount,$user_id,$order_id,$unm){
        $senderAmt = $balance =  0;
        $sender_wallet = Wallet::where('userid',$user_id)->first();
        if($sender_wallet->earning >= $amount){
            $senderAmt = $sender_wallet->earning;
            $effect_amt =$sender_wallet->earning-$amount;
            $earn =  $senderAmt-$amount;
            $upData = array('earning'=> $effect_amt);
            $earnAdd = $amount;
            $uniAdd = $amtAdd = 0;
            $balance = $effect_amt+$sender_wallet->amount+$sender_wallet->unicash;
        }else if($sender_wallet->unicash+$sender_wallet->earning >= $amount){
            $earn = 0;

            $remainAmt = $amount-$sender_wallet->earning;
            $uni = $sender_wallet->unicash-$remainAmt;
            $senderAmt = $sender_wallet->earning+$sender_wallet->unicash;
            $effect_amt = $sender_wallet->unicash+$sender_wallet->earning-$amount;
            $upData = array('earning'=>$earn,'unicash'=> $uni);
            $earnAdd = $sender_wallet->earning;
            $uniAdd = $remainAmt;
            $amtAdd = 0; $balance = $effect_amt+$sender_wallet->amount;

        }else if($sender_wallet->earning+$sender_wallet->unicash+$sender_wallet->amount >= $amount){
            $earn = 0;
            $uni = 0;
            $remainAmt = $amount-$sender_wallet->earning;
            $remainAmt1 = $remainAmt-$sender_wallet->unicash;

            $senderAmount = $sender_wallet->amount-$remainAmt1;
            $senderAmt = $sender_wallet->earning+$sender_wallet->unicash+$sender_wallet->amount;
            $effect_amt = $sender_wallet->unicash+$sender_wallet->earning+$sender_wallet->amount-$amount;
            $upData = array('earning'=>$earn,'unicash'=> $uni,'amount'=>$senderAmount);
            $earnAdd = $sender_wallet->earning;
            $uniAdd = $sender_wallet->unicash;
            $amtAdd = $remainAmt1; 
            $balance = $effect_amt;
        }

        if($senderAmt >= $amount ){	
            $q = Wallet::where('userid',$user_id)->update($upData);
            if($q){
                $walletBal = Wallet::where('userid',$user_id)->first();
                $qq = new walletTransition();
                $qq->user_id = $user_id;
                $qq->unm = $unm;
				
                $qq->debit = $amount;
                $qq->credit = 0;
                $qq->remark = '';
                $qq->balance = $balance;
                $qq->transition_type = 'Reset_epin';
                $qq->in_type = 'Your Wallet is Debited '.$amount.' for Service Charge to UNI'.$order_id.'';
                $qq->description = 'amount : '.$amtAdd.' unicash: '.$uniAdd.' earning : '.$earnAdd.' uniPoint: '.$walletBal->bp;
                $qq->amount = $amtAdd;
                $qq->unicash = $uniAdd;
                $qq->earning = $earnAdd;
                $qq->unipoint = 0;
                $qq->ord_id = $order_id;

                if($qq->save()){
                    $out = array('msg'=>'true','earn'=>$earnAdd,'uni'=>$uniAdd,'amt'=>$amtAdd,'balance'=>$balance);
                }else{
                    $out = array('msg'=>'not-done-transition');
                }
                
            }else{
                $out = array('msg'=>'wlllet not updated');
            }
        }else{
            $out = array('msg'=>'Insufficient Balance.....');
        }

        return $out;
    }
    
  

}
