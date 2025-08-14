<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Jobs\RegisterJob;
use App\Models\LevelCount;
use App\Models\Royalty;
use App\Models\UniQrcode;
use App\Models\User;
use App\Models\UsersLvlS1;
use App\Models\Wallet;
use App\Models\WalletTransition;
use App\Services\SmsService;
use App\Models\UserKyc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function sendOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|string',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            UsersLvlS1::where('mobile', $request->mobile_no)->delete();

            $otp = rand(111111, 999999);

            // $sms = new SmsService();
            // $res = $sms->sendMessage('reg_otp', $request->mobile_no, $otp, '', '');
            // if (empty($res['status'])) {
            //     return $this->failRes($res['msg']);
            // }

            $save = new UsersLvlS1();
            $save->user_id = 0;
            $save->mobile = $request->mobile_no;
            $save->mobile_otp = md5($otp);
            $save->otp_expired = strtotime(Carbon::now()->addMinutes(config('global.otpExpired')));
            if ($save->save()) {
                $existUser = User::where('mobile', $request->mobile_no)->first();
                return $this->recordRes(
                    [
                        'expire_time' => $save->otp_expired,
                        'time' => config('global.otpExpired'),
                        'new_user' => $existUser ? false : true,
                        'otp' => $otp
                    ],
                    'OTP Sent on ' . $request->mobile_no
                );
            }
            return $this->failRes('Something Went Wrong, OTP not Sent.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required|numeric|digits:6',
                'mobile_no' => 'required|digits:10',
                'ref_id' => 'nullable|string',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            $userLvl = UsersLvlS1::where('mobile', $request->mobile_no)->first();
            if (empty($userLvl))
                return $this->failRes('Invalid Mobile No.');

            if (strtotime(now()) >= $userLvl->otp_expired)
                return $this->failRes('OTP is Expired.');

            if ($userLvl->mobile_otp != md5($request->otp))
                return $this->failRes('OTP Mismatch, OTP not Verified.');

            DB::beginTransaction();
            $user = User::with(['Wallet', 'UserKyc', 'Royalty'])->where('mobile', $userLvl->mobile)->first();
            if (empty($user)) {
                $lastuser = User::select('id', 'user_id')->orderBy('id', 'DESC')->first();
                $user_id = !empty($lastuser->user_id) ? $lastuser->user_id + 1 : 1;
                $user_nm = rand(10000000, 99999999);

                $user = new User();
                $user->ref_id = $request->ref_id ?? null;
                $user->user_id = $user_id;
                $user->user_num = $user_nm;
                $user->alpha_num_uid = 'UNI' . $user_id;
                $user->mobile = $userLvl->mobile;
                $user->isactive = 1;
                if (!$user->save()) {
                    DB::rollBack();
                    return $this->failRes('Something Went wrong in Otp Verification process.');
                }

                if (!empty($request->ref_id)) {
                    $res = $this->saveUserDetail($request->ref_id, $user_nm, $user_id);
                    if (!$res['status']) {
                        DB::rollBack();
                        return $this->failRes($res['msg'] ?? '');
                    }
                }
            } else {
                $user_nm = $user->user_num;
            }
            UsersLvlS1::where('mobile', $userLvl->mobile)->delete();

            // generate jwt token
            $token = JWTAuth::fromUser($user);
            if (!$token) {
                DB::rollBack();
                return $this->failRes('Invalid Credentials.');
            }

            $data = $this->profileData($user,$token);
            DB::commit();
            return $this->recordRes($data, 'Otp Verified, Registered Successfully!');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function profileData($user,$token = null)
    {
        $data = [
            'user_num' => $user->user_num,
            'user_id' => $user->user_id ?? '',
            'alpha_user_id' => $user->alpha_num_uid ?? '',
            'full_name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'mobile_no' => $user->mobile ?? '',
            'gender' => $user->gender ?? '',
            'dob' => $user->userKyc->dob ?? '',
            'city' => $user->userKyc->district ?? '',
            'state' => $user->userKyc->state ?? '',
            'pincode' => $user->userKyc->pincode ?? '',
            'address' => $user->userKyc->address ?? '',
            'role' => $user->role ?? '',
            'udpay_user' =>in_array($user->role,['distributor','customer']) ? 1 : 0,
            'is_active' => $user->isactive ?? '',
            'landmark' => $user->userKyc->locality ?? '',
            'created_at' => $user->created_at ?? '',
            'updated_at' => $user->updated_at ?? '',
        ];
        if($token){
            $data['authorisation'] = [
                'token' => $token,
                'type' => 'bearer',
            ];
        }
        return $data;
    }

    public function getProfile(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $user = User::with(['UserKyc'])->find($user_id);

            $data = $this->profileData($user);
            return $this->recordRes($data, 'Profile Fetched Successfully!');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    public function updateProfile(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string',
                'gender' => 'required|string|in:male,female,other',
                'dob' => 'required|date',
                'email' => 'required|email',
                'city' => 'required|string',
                'state' => 'required|string',
                'pincode' => 'required|digits:6',
                'address' => 'required|string',
                'landmark' => 'required|string',
            ],[
                'full_name.required' => 'Full name is required.',
                'gender.required' => 'Gender is required.',
                'dob.required' => 'Date of birth is required.',
                'email.required' => 'Email is required.',
                'city.required' => 'City is required.',
                'state.required' => 'State is required.',
                'pincode.required' => 'Pincode is required.',
                'address.required' => 'Address is required.',
                'landmark.required' => 'Landmark is required.',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            $existUser = User::where('email', $request->email)->where('id', '!=', Auth::user()->id)->first();
            if($existUser){
                return $this->failRes('Email already exists.');
            }
           

            DB::beginTransaction();
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            $user->name = $request->full_name;
            $user->gender = $request->gender;
            $user->email = $request->email;
            if(!$user->save()){
                DB::rollBack();
                return $this->failRes('Something went wrong, Profile not updated.');
            }
                $userKyc = UserKyc::where('userId', $user->user_id)->first();
                if(empty($userKyc)){
                    $userKyc = new UserKyc();
                    $userKyc->userId = $user->user_id;
                }
                $userKyc->name = $request->full_name;
                $userKyc->gender = $request->gender;
                $userKyc->dob = $request->dob;
                $userKyc->district = $request->city;
                $userKyc->state = $request->state;
                $userKyc->pincode = $request->pincode;
                $userKyc->address = $request->address;
                $userKyc->locality = $request->landmark;
                if(!$userKyc->save()){
                    DB::rollBack();
                    return $this->failRes('Something went wrong, Profile not updated.');
                }
           
            DB::commit();
            return $this->recordRes($this->profileData($user), 'Profile Updated Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failRes($e->getMessage());
        }
    }

    public function currentUserDetail()
    {
        try {
            $user_id = Auth::user()->id;
            $user = User::with(['UserKyc', 'Wallet', 'Royalty'])->find($user_id);

            $data = $this->userDataWithKYC($user);
            return $this->recordRes($data, 'Profile Fetched Successfully!');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function userDataWithKYC($user, $token = null)
    {
        $post = 'user';
        $pgimg = 'user.jpg';
        if (!empty($user->Royalty->diamond)) {
            $post = 'Diamond';
            $pgimg = 'dimond.jpeg';
        } else if (!empty($user->Royalty->gold)) {
            $post = 'gold';
            $pgimg = 'goldstar.jpeg';
        } else if (!empty($user->Royalty->silver)) {
            $post = 'silver';
            $pgimg = 'silverstar.jpeg';
        } else if (!empty($user->Royalty->bronze)) {
            $post = 'bronze';
            $pgimg = 'bronze.jpeg';
        } else if (!empty($user->Royalty->pb5)) {
            $post = 'uni_star';
            $pgimg = 'unistar.svg';
        } else if ($user->isactive == 1) {
            $post = 'distributor';
            $pgimg = 'user.jpg';
        }

        $userCount = LevelCount::where('parent_id', $user->user_nm)->distinct('child_id')->count('child_id');
        $unicashTotal = WalletTransition::where('credit', '>', 0)->where('user_id', $user->user_id)->whereIn('transition_type', [
            'ins_cashback', 'kico_cashback', 'ubazar_cashback',
            'bus_cashback', 'inr_cashback', 'recharge_cashback'
        ])->sum('credit');

        $wallet = $user->Wallet;

        $kycRecord = $this->kycDetails($user->UserKyc ?? array());
        $totalWalletAmt = ($wallet->amount ?? 0) + ($wallet->earning ?? 0) + ($wallet->unicash ?? 0);

        $dataArr = array(
            'user_num' => $user->user_num,
            'user_id' => $user->user_id ?? '',
            'full_name' => $user->name ?? '',
            'user_email' => $user->email ?? '',
            'user_phone' => $user->mobile ?? '',
            'alpha_user_id' => $user->alpha_num_uid ?? '',
            'epin' => $user->epin ? 1 : 0,
            'is_active' => $user->isactive ?? 0,
            'post' => $post,
            'user_count' => (int) $userCount,
            'wallet_balance' => (float) round($totalWalletAmt, 2),
            'bp' => (float) ($wallet->bp ?? 0),
            'post_icon' => url('/') . 'assets/images/post/' . $pgimg,
            'total_unicash' => (float) ($unicashTotal),
            'available_unicash' => (float) ($wallet->unicash ?? 0),
            'qrcode' => '',//$this->generateQRCode($user->user_num),
            'post_membership' => ($user->planMem == 1) ? 'Active' : 'Inactive',
            'kyc_flag' => $user->UserKyc->kyc_flag ?? 0,
            'pan_flag' => $user->UserKyc->pan_flag ?? 0,
            'aadhar_flag' => $user->UserKyc->aadhar_flag ?? 0,
            'self_flag' => $user->UserKyc->self_flag ?? 0,
            'personal_details' => $kycRecord['personal_details'] ?? null,
            'bank_details' => $kycRecord['bank_details'] ?? null,
            'docs' => $kycRecord['docs'] ?? null,
        );
        if ($token) {
            $dataArr['authorisation'] = [
                'token' => $token,
                'type' => 'bearer',
            ];
        }
        return $dataArr;
    }

    private function kycDetails($data)
    {
        $record = [];
        if (!empty($data->name) || !empty($data->mobile)) {
            $record['personal_details'] = [
                'name' => $data->name ?? '',
                'address' => $data->address ?? '',
                'mobile' => $data->mobile ?? '',
                'gender' => strtolower($data->gender ?? ''),
                'dob' => $data->dob ?? '',
                'state' => $data->state ?? '',
                'district' => $data->district ?? '',
                'locality' => $data->locality ?? '',
                'pincode' => !empty($data->pincode) ? (int) $data->pincode : 0,
                'work' => $data->work ?? '',
                'nominee' => $data->nominee ?? '',
                'relation' => $data->relation ?? ''
            ];
        }

        if (!empty($data->bank) || !empty($data->ac_number)) {
            $record['bank_details'] = [
                'name' => $data->holder_name ?? '',
                'bank' => $data->bank ?? '',
                'branch' => $data->branch ?? '',
                'ifsc_code' => $data->ifsc_code ?? '',
                'ac_number' => $data->ac_number ?? '',
                'id_proof' => $data->id_proof ?? '',
                'bank_doc' => $data->bank_doc ?? '',
            ];
        }
        if (!empty($data->self) || !empty($data->pan_number) || !empty($data->aadhar_number)) {
            $record['docs'] = [
                'pan_number' => $data->pan_number ?? '',
                'aadhar_number' => $data->aadhar_number ?? '',
                'pan' => $data->pan ?? '',
                'doc_type' => strtolower(str_replace(' ', '_', $data->doc_type ?? 'aadhar')),
                'aadhar1' => $data->aadhar1 ?? '',
                'aadhar2' => $data->aadhar2 ?? '',
                'selfie' => $data->self ?? '',
            ];
        }
        return $record;
    }

    private function generateQRCode($uid)
    {
        // ->where('qr_type', 'user')
        $qr = UniQrcode::where('uid', $uid)->first();
        if (!empty($qr->img))
            return $qr->img;

        $folderPath = public_path('qrcode');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }

        $filePath = $folderPath . '/' . $uid . '.svg';

        FacadesQrCode::size(400)->generate($uid, $filePath);
        Storage::disk('public')->put('qrcode/' . $uid . '.svg', file_get_contents($filePath));
        $qrCodeUrl = Storage::disk('public')->url('qrcode/' . $uid . '.svg');
        if ($qrCodeUrl) {
            $save = new UniQrcode();
            $save->img = asset('qrcode/' . $uid . '.svg');
            $save->uid = $uid;
            $save->status = 0;
            if ($save->save())
                return $save->img;
        }
        return false;
    }

    // public function userAvailability(CheckAvailability $request)
    // {
    //     try {
    //         $phoneFlag = $emailFlag = true;
    //         $uidFlag = false;

    //         if (empty($request->uid) && empty($request->phone) && empty($request->email))
    //             return $this->failRes('Please Provide at least one parameter.');

    //         // check user id here
    //         if (!empty($request->uid)) {
    //             $uidCount = User::where('user_nm', $request->uid)->count();
    //             $uidFlag = false;
    //             if ($uidCount > 0)
    //                 $uidFlag = true;
    //         }

    //         // check phone here
    //         if (!empty($request->phone)) {
    //             $phoeCount = User::where('mobile', $request->phone)->count();
    //             $phoneFlag = true;
    //             if ($phoeCount > 0)
    //                 $phoneFlag = false;
    //         }

    //         // check email here
    //         if (!empty($request->email)) {
    //             $emailCount = User::where('email', $request->email)->count();
    //             $emailFlag = true;
    //             if ($emailCount > 0)
    //                 $emailFlag = false;
    //         }

    //         $query = User::select('user_id', 'fname', 'mobile');
    //         if (!empty($request->uid)) {
    //             $query->where('user_nm', $request->uid);
    //         }
    //         if (!empty($request->phone))
    //             $query->where('mobile', $request->phone);
    //         if (!empty($request->email))
    //             $query->where('email', $request->email);

    //         $user = $query->first();

    //         // if ($uidFlag && $phoneFlag && $emailFlag) {
    //         //     if (!$uidFlag) {
    //         //         return $this->failRes('Referal Id Not Exist.');
    //         //     }
    //         //     return $this->failRes('User Not Exist.');
    //         // }

    //         $array = [
    //             'uid' => $uidFlag,
    //             'phone' => $phoneFlag,
    //             'email' => $emailFlag,
    //         ];
    //         if ((!empty($user)) && ($uidFlag || !$phoneFlag || !$emailFlag)) {
    //             $array['user_data'] = [
    //                 'uid' => $user->user_id ?? '',
    //                 'name' => $user->fname ?? '',
    //                 'mobile' => $user->mobile ?? ''
    //             ];
    //             $msg = 'User Not Exist.';
    //         }
    //         $msg = 'User Not Existt.';
    //         if ($uidFlag) {
    //             $msg = 'Referal Id Found.';
    //         } elseif (!$phoneFlag || !$emailFlag) {
    //             $msg = 'User Exist.';
    //             return response()->json([
    //                 'status' => false,
    //                 'code' => 403,
    //                 'msg' => $msg,
    //                 'record' => $array
    //             ], 403);
    //         } elseif ($phoneFlag || $emailFlag) {
    //             $msg = 'User Not Exist.';
    //             if (!$uidFlag && $request->type == 'uid') {
    //                 return $this->failRes('Referal Id Not Found.');
    //             }
    //         }
    //         return $this->recordResMsg($array, $msg);
    //     } catch (Exception $e) {
    //         return $this->failRes($e->getMessage());
    //     }
    // }

    private function saveUserDetail($refered_by, $user_nm, $user_id)
    {
        $wallet = $this->insertWallet($user_id, $user_nm);
        if (!$wallet) {
            return ['status' => false, 'msg' => 'Something went wrong, Not insert in User Wallet'];
        } else {
            $user = User::where('user_id', $user_id)->first();
            $user->uflag = 4;
            $user->save();
        }

        $payload = ['user_id' => $user_id, 'unm' => $user_nm, 'refered_by' => $refered_by, 'org' => $org = ''];
        // dispatch(new RegisterJob($payload));  // here run job queue  hereeeee

        $walletUpdate = Wallet::where('unm', $refered_by)->first();
        if (empty($walletUpdate)) {
            return ['status' => true, 'msg' => 'Wallet Amount Updated Successfully!'];
        }
        $existbp = $walletUpdate->bp ?? 0;
        $walletUpdate->bp = $existbp + 100;

        if ($walletUpdate->save()) {
            // $sms = new SMSSender;
            // $msgSend = $sms->sendMessage('reg_msg', $mobile, '', 'UNI' . $user_nm, $password);
            // if (!$msgSend)
            //     Log::warning('Something went wrong, SMS not sent - UserId', [$user_nm]);

            // for send mail by job queue
            //  dispatch(new MailJob(['email' => $email, 'user_name' => 'UNI' . $user_nm, 'password' => $password, 'full_name' => $fname], 'sign_up'));

            return ['status' => true, 'msg' => 'Wallet Amount Updated Successfully!'];
        } else {
            Log::warning('Something went wrong, Wallet Amount not updated - UserId', [$user_nm]);
            return ['status' => true, 'msg' => 'Something went wrong, Wallet Amount not updated'];
        }
    }

    private function insertWallet($id, $unm)
    {
        $save = new Wallet();
        $save->userid = $id;
        $save->unm = $unm;
        $save->bp = 100;
        $save->amount = 0;
        $save->earning = 0;
        $save->sp = 0;
        $save->unicash = 0;
        $save->sec_val = 0;
        if ($save->save())
            return true;

        return false;
    }
}
