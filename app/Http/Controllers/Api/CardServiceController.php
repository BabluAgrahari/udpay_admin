<?php
//use Log;
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\Products;
use App\Models\Product\Cart;
use App\Models\Wallet;
use App\Models\User;
use DB;
use Exception;
use App\Services\SquareFin\SquareFin;
use App\Models\Lead;

class CardServiceController extends Controller
{

    public function getCardList(Request $request){
        try { 
            $squareFin = new SquareFin();
            $productList = $squareFin->getProductList();

            if($productList['status']){
                $data = $productList['data'];
                return $this->recordRes($data);
            }else{
                return $this->failRes($productList['message']);
            }
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    public function saveLead(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|numeric|digits:10',
                'pan_number' => 'required|alpha_num',
                'card_id' => 'required',
            ]);

            if($validator->fails()){
                return $this->validationRes($validator->errors());
            }

            $payload = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'pan_number' => $request->pan_number,
                'product_id' => $request->card_id,
            ];
            $squareFin = new SquareFin();
            $lead = $squareFin->createLead($payload);

            if(!$lead['status']){
                return $this->failRes($lead['message']);
            }
                
                $save = new Lead();
                $save->name = $request->first_name . ' ' . $request->last_name;
                $save->email = $request->email;
                $save->mobile = $request->mobile;
                $save->pan_number = $request->pan_number;
                $save->product_id = $request->card_id;
                $save->lead_id = $lead['lead_id'];
                $save->link = $lead['link'];
                $save->refferd_id = $lead['refferd_id'];
                $save->campaign_url = $lead['campaign_url'];
                $save->status= 'pending';
                if($save->save()){
                    return $this->successRes('Lead Created Successfully');
                }else{
                    return $this->failRes('Lead Creation Failed');
                }

        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    public function getLeadStatus(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'lead_id' => 'required',
            ]);

            if($validator->fails()){
                return $this->validationRes($validator->errors());
            }

            $lead = Lead::where('lead_id', $request->lead_id)->first();
            if(!$lead){
                return $this->failRes('Lead not found');
            }
            $squareFin = new SquareFin();
            $status = $squareFin->getStatus($request->lead_id);
            if(!$status['status']){
                return $this->failRes($status['message']);
            }

            $lead->status = $status['status'];
            if($lead->save()){
                return $this->successRes('Lead Status Updated Successfully');
            }else{
                return $this->failRes('Lead Status Update Failed');
            }
            
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }   

}