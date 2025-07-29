<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveMerchantRequest;
use App\Models\Merchant;
use App\Models\Merchant_kyc;
use App\Models\User;
use App\Models\Wallet;
class MerchantController extends Controller{
   
    function save_merchant_st1(Request $request){
        $uid = explode('-',$request->userId);
       
        if($uid[0] == 'Unique'){
            $check = Merchant::where('uid',$uid[1])->first();
            if($check){
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'msg' => 'already',
                    'txt' => 'User Already Registered......'
                ]);
            
            }else{
                if($request->accept == 1){                   
                    $user = getUser($uid[1]);
                    if($user['status'] == true){
                        $save = new Merchant();
                        $save->shop_name = $request->shop_name;
                        $save->commission  = $request->commission;
                        $save->pincode  = $request->pincode;
                        $save->city  = $request->city;
                        $save->state  = $request->state;
                        $save->shop_address  = $request->shop_address;
                        $save->category = $request->category;
                        $save->sub_cat = $request->sub_cat;
                        $save->gst_number = $request->gst_number;
                        $save->uid = $uid[1];
                        
                        if($save->save()){
                            if($save->id > 9){
                                $alpha_num_uid = "UNIM".$save->id;
                            }else{
                                $alpha_num_uid = "UNIM100".$save->id;
                            }
                            $mid = Merchant::find($save->id);
                            $mid->mid =$save->id;
                            $mid->alpha_num_id = $alpha_num_uid;
                            $mid->save();
                            $qr = generateQRCode('6375245144','merchant',$uid[1]); 
                            return response()->json([
                                'status'=>true,
                                'code'=>200,
                                'msg' =>'Success',
                                'txt'=>'Merchant Successfully Registered....',
                                'merchant_id'=>$save->id,
                            ]);
                        }else{
                            return response()->json([
                                'status'=>false,
                                'code'=>400,
                                'msg' =>'FAIL',
                                'txt' =>'Something Went Wrong....'
                            ]);
                        }
                    }else{return $user;}                    
                }else{
                    return response()->json([
                        'status'=>false,
                        'code'=>400,
                        'msg'=>'FAIL','txt'=>'Please Accept Term & Condition First.....'
                    ]);                    
                }
            }
        }else{
            return response()->json([
                'status'=>false,
                'code'=>400,
                'msg'=>'FAIL','txt'=>'Invalid User Id...'
            ]);
           
        }
    }

    public function save_merchant_st2(Request $request){
         
        $uid = $request->userId;
        $uid = explode('-',$uid);
        $merchant_id = $request->merchant_id;
        $name = $request->owner_name;
        $email = $request->owner_email;
        $pincode = $request->owner_pincode;
        $city = $request->owner_city;
        $state = $request->owner_state;
        $address = $request->owner_address;
        $mobile = $request->owner_mobile;
        
            if($uid[0] == 'Unique'){
                if($merchant_id != ''){
                    $res = Merchant::where('mid',$merchant_id)->first();
                    if($res){
                        if(check_mobile($mobile) ==true){
                            if(check_mail($email) ==true){
                                $qry = Merchant::find($res->id);
                                $qry->owner_name = $name;
                                $qry->owner_email = $email;
                                $qry->owner_pincode = $pincode;
                                $qry->owner_city = $city;
                                $qry->owner_state = $state;
                                $qry->owner_address = $address;
                                $qry->owner_mobile = $mobile;
                                if($qry->save()){
                                    return response()->json([
                                        'status'=>true,
                                        'code'=>200,
                                        'msg' =>'Success','txt'=>'Step 2 Completed.....',
                                        'merchant_id'=>$merchant_id
                                    ]);
                                }else{
                                    return response()->json([
                                        'status'=>false,
                                        'code'=>400,
                                        'msg' =>'FAIL','txt'=>'Something Went Wrong.....',
                                    ]);
                                }
								
                            }else{
                                return response()->json([
                                    'status'=>false,
                                    'code'=>400,
                                    'msg'=>'FAIL','txt'=>'Email Already Registered....'
                                ]);
                            }
                        }else{
                            return response()->json([
                                'status'=>false,
                                'code'=>400,
                                'msg'=>'FAIL','txt'=>'Mobile Already Registered....'
                            ]);
                            
                        }
                    }else{
                        return response()->json([
                            'status'=>false,
                            'code'=>400,
                            'msg'=>'FAIL','txt'=>'User id OR Merchant id not available....'
                        ]);
                       
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'code'=>400,
                        'msg'=>'FAIL','txt'=>'Merchant id Required.....'
                    ]);
                    
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'code'=>400,
                    'msg'=>'FAIL','txt'=>'Invalid User Id.....'
                ]);
                
            }
    }

    public function save_merchant_st3(SaveMerchantRequest $request){
        //print_r($request->all());
        //SaveMerchantRequest($request);
        $uid = explode('-',$request->userId);
        if($request->op_key == 'self'){
            $save = new Merchant_kyc();
            $save->mid = $request->mid;
            $save->uid = $uid[1];
            if($request->hasFile('shop_img')){
                $file = $request->file('shop_img');
                $url = '/merchant/shop';
                $upload = uploadImg($file,$url);
                // Getting a unique filename to prevent overwriting
                // Save the filename to the database or perform any other necessary operations
                $save->shop_img = $upload;
            }

            if ($request->hasFile('selfie')){
                $file = $request->file('selfie');
            
                $url = '/merchant/selfie';
                $upload = uploadImg($file,$url);
                $save->selfie_img = $upload;
            
            }   
            $save->self_flag = 1;         
        }else if($request->op_key == 'pan'){
            $qry = Merchant_kyc::where('mid',$request->mid)->first();
            if($qry){
                $save = Merchant_kyc::find($qry->id);
                if($request->hasFile('pan_img')){
                    $file = $request->file('pan_img');
                    $url = '/merchant/pan';
                    $upload = uploadImg($file,$url);
                    // Getting a unique filename to prevent overwriting
                    // Save the filename to the database or perform any other necessary operations
                    $save->pan_img = $upload;
                    $save->pan_flag = 1;   
                }
                
                $save->pan_number = $request->pan_number;
            }else{
                return response()->json([
                    'satus' => false,
                    'code' => 404,
                    'msg' => 'FAIL',
                    'txt' => 'Record not found.....'
                ]);  
            }
            
        }else if($request->op_key == 'aadhar'){
            $url = '/merchant/aadhar';
            $qry = Merchant_kyc::where('mid',$request->mid)->first();
            if($qry){
                $save = Merchant_kyc::find($qry->id);

                if($request->hasFile('aadhar1')){
                    $file = $request->file('aadhar1');
                    $upload = uploadImg($file,$url);
                    // Getting a unique filename to prevent overwriting
                    // Save the filename to the database or perform any other necessary operations
                    $save->aadhar1 = $upload;
                      
                }
                if($request->hasFile('aadhar2')){
                    $file = $request->file('aadhar2');
                    $upload = uploadImg($file,$url);
                    $save->aadhar2 = $upload;
                    $save->aadhar_flag = 1;   
                }

                
                $save->aadhar_number = $request->aadhar_number;
            }else{
                return response()->json([
                    'satus' => false,
                    'code' => 404,
                    'msg' => 'FAIL',
                    'txt' => 'Record not found.....'
                ]);  
            }
        }else if($request->op_key == 'bank'){
            $url = '/merchant/bank';
            $qry = Merchant_kyc::where('mid',$request->mid)->first();
            if($qry){
                $save = Merchant_kyc::find($qry->id);

                if($request->hasFile('bank_proof_img')){
                    $file = $request->file('bank_proof_img');
                    $upload = uploadImg($file,$url);
                    $save->bank_proof_img = $upload;  
                }
                
                $save->ac_number = $request->ac_number;
                $save->bank_name = $request->bank_name;
                $save->branch_name = $request->branch_name;
                $save->ifsc = $request->ifsc;
                $save->bank_flag = 1;
            }else{
                return response()->json([
                    'satus' => false,
                    'code' => 404,
                    'msg' => 'FAIL',
                    'txt' => 'Record not found.....'
                ]);  
            }
            
        }else{
            return response()->json([
                'satus' => false,
                'code' => 400,
                'msg' => 'FAIL',
                'txt' => 'Missmatch op key....'
            ]);
        }
        if($save->save()){
            return response()->json([
                'satus' => true,
                'code' => 200,
                'msg' => 'Success',
                'txt' => 'Documents Uploaded.....'
            ]);
        }else{
            return response()->json([
                'satus' => false,
                'code' => 400,
                'msg' => 'FAIL',
                'txt' => 'Something Went Wrong.....'
            ]);
        }
    }

    function merchantScan(Request $request){
        $validator = Validator::make($request->all(),[
           'userId' => 'required',
           'mobile' => 'required|digits:10', 
        ]);
        if($validator->fails())
            return validationRes($validator->messages());

        $uid = explode('-',$request->userId);
        $merchant = Merchant::where('owner_mobile',$request->mobile)->first();
        $user = getUser($uid[1]);
        if($user['status'] == true){
            if($merchant){
                $userDtl = Wallet::where('userid',$uid[1])->first();
                $walletAmt =$userDtl->amount+$userDtl->unicash+$userDtl->earning; 
                $data = array('mid'=>$merchant->mid,'shopName'=>$merchant->shop_name,'bp'=>$userDtl->bp,'amount'=>$walletAmt);

                return response()->json([
                    'status'=>true,
                    'code' => 200,
                    'msg'=>'Success',
                    'data'=>$data
                ]);
            }else{
                return response()->json([
                    'status'=>false,
                    'code' => 404,
                    'msg'=>'FAIL',
                    'txt'=>'Merchant Not Found.....'
                ]);
            }
        }else{
            return $user;
        }
       
    }

    function payToMerchant(Request $request){
        $validator = Validator::make($request->all(),[
            'userId'=>'required',
            'mid' => 'required',
            'amount'=>'required|numeric',
            
        ]);
        $uid = explode('-',$request->userId);
        if($validator->fails())
            return validationRes($validator->messages());
        if($request->amount >0){
            $orderId = generateOTP1(8);
            $userDebit = self::debitUserWallet($uid[1],$request->amount,$order);
            if($userDebit == true){
                $merchantCredit = self::creditMerchantWallet($request->mid,);
            }else{
                return $userDebit;
            }
            


        }else{
            return response()->json([
                'status'=>false,
                'code' =>400,
                'msg'=>'FAIL',
                'txt' =>'Amount Should be greater then 0'
            ]);
        }

    }

    function debitUserWallet($uid,$amount){
        $sender_wallet = getWalletRes($uid);
        $bp = $amount*0.05;
        if($sender_wallet->bp >= $bp){
            $amount = $amount-$bp;
        }else{
            $amount = $amount;
        }
        if($sender_wallet->earning >= $amount){
            $senderAmt = $sender_wallet->earning;
            $effect_amt = $sender_wallet->earning-$amount;
            $balance = $effect_amt+$sender_wallet->unicash+$sender_wallet->amount;
            $earn =  $senderAmt-$amount;
            $earn1 =  $amount;
            $upData = array('earning'=> $effect_amt);
            $description = "Unicash: 0 earning: ".$sender_wallet->earning." amount: 0";
        }else if($sender_wallet->unicash+$sender_wallet->earning >= $amount){
            $earn = 0;
            $earn1 = $sender_wallet->earning;
            $remainAmt = $amount-$sender_wallet->earning;
            $uni1 = $remainAmt;
            $uni = $sender_wallet->unicash-$remainAmt;
            $senderAmt = $sender_wallet->earning+$sender_wallet->unicash;
            $effect_amt = $sender_wallet->unicash+$sender_wallet->earning-$amount;
            $balance = $effect_amt+$sender_wallet->amount;
            $upData = array('earning'=>$earn,'unicash'=> $uni);
            $description = "Unicash: ".$remainAmt." earning: ".$sender_wallet->earning." amount: 0";
        }else if($sender_wallet->earning+$sender_wallet->unicash+$sender_wallet->amount >= $amount){
            $earn = 0;
            $uni = 0;
            $earn1 = $sender_wallet->earning;
            $uni1 = $sender_wallet->unicash;
            $remainAmt = $amount-$sender_wallet->earning;
            $remainAmt1 = $remainAmt-$sender_wallet->unicash;

            $senderAmount = $sender_wallet->amount-$remainAmt1;
            $amt = $remainAmt1;
            $senderAmt = $sender_wallet->earning+$sender_wallet->unicash+$sender_wallet->amount;
            $effect_amt = $sender_wallet->unicash+$sender_wallet->earning+$sender_wallet->amount-$amount;
            $balance = $effect_amt;
            $upData = array('earning'=>$earn,'unicash'=> $uni,'amount'=>$senderAmount);
            $description = "Unicash: ".$sender_wallet->unicash." earning: ".$sender_wallet->earning." amount: ".$remainAmt1;
            if($senderAmt >= $amount ){
                $q = Wallet::where('userid',$sender)->update('wallet',$upData);
                if($q){
                    if($sender <= 11){
                        $sen_uid = "UNIPAY";
                    }else{
                        $sen_uid = $user_info->alpha_num_uid;
                    }
                    $qry1 = new Wallet_transition();
                    $qry1->user_id = $receiver;
                    $qry1->credit = $amount;
                    $qry1->debit = 0;
                    $qry1->balance = $walletAmt;
                    $qry1->in_type = 'You have Recevied '.$amount.' from '.$sen_uid;
                    $qry1->created_on = date('Y-m-d H:i:s');
                    $qry1->remark = $remark;
                    $qry1->amount = $amount;
                    $qry1->ord_id = $orderId;
                    
                    if($qry1->save()){
                        $msg = true;
                        
                    }else{
                        return response()->json([
                            'status' =>false,
                            'ccode' => 400,
                            'msg'=>'no_money','txt'=>'insufficient funds....'
                        ]);
                       
                    }

                }else{
                    return response()->json([
                        'status' =>false,
                        'ccode' => 400,
                        'msg'=>'err','txt'=>'Something Went Wrong....'
                    ]);
                     
                }
            }else{
                return response()->json([
                    'status' =>false,
                    'ccode' => 400,
                    'msg'=>'no_money','txt'=>'insufficient funds....'
                ]);
                
            }
        }
    }
}
