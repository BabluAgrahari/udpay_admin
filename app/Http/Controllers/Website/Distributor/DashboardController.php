<?php
 namespace App\Http\Controllers\Website\Distributor;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use App\Models\Category;
 use App\Models\Product;
 use App\Models\UserKyc;

 use App\Models\User;
 class DashboardController extends Controller
 {

    public function index($type = 'dashboard'){

      $data['type'] = $type;

      if($type == 'kyc'){
         $data['user'] = User::where('user_id',auth()->user()->user_id)->first();

         $data['kyc'] = UserKyc::where('userId',auth()->user()->user_id)->first();
       
      }

      return view('Website.Distributor.dashboard',$data);
    }


 }