<?php

namespace App\Http\Controllers\Api\Deals;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Deals\Products;

class DealProductController extends Controller
{
    
    public function productList(Request $request){
        
        $res = Products::get();
        if($res){
            return response()->json([
            'status'=>true,
            'code'=>200,
            'msg'=>'Success',
            'data'=>$res,

            ]);
        }else{
            return response()->json([
                'status'=>false,
                'code'=>400,
                'msg'=>'FAIL',    
            ]);
        }
        
    }

    public function productListSingle(Request $request){
        $res = Products::where('id',$request->pro_id)->first();
        if($res){
            return response()->json([
            'status'=>true,
            'code'=>200,
            'msg'=>'Success',
            'data'=>$res,

            ]);
        }else{
            return response()->json([
                'status'=>false,
                'code'=>400,
                'msg'=>'FAIL',    
            ]);
        }
        
    }

    //cart

    public function addToCart(Request $request){
        
        if($res){
            return response()->json([
            'status'=>true,
            'code'=>200,
            'msg'=>'Success',
            'data'=>$res,

            ]);
        }else{
            return response()->json([
                'status'=>false,
                'code'=>400,
                'msg'=>'FAIL',    
            ]);
        }
        
    }

}
