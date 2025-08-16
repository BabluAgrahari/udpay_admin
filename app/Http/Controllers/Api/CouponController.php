<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        try {
            $coupons = Coupon::active()->get();
            $coupons = $coupons->map(function ($coupon) {
                return $this->field($coupon);
            });
            return $this->recordsRes($coupons);
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function field($coupon)
    {
        return [
            'id' => (int) $coupon->id,
            'code' => (string) $coupon->code ?? '',
            'name' => (string) $coupon->name ?? '',
            'description' => (string) $coupon->description ?? '',
            'discount_type' => (string) $coupon->discount_type ?? '',
            'discount_value' => (float) $coupon->discount_value ?? 0,
            'minimum_amount' => (float) $coupon->minimum_amount ?? 0,
            'maximum_discount' => (string) $coupon->maximum_discount ?? '',
            'usage_limit' => (int) $coupon->usage_limit ?? 0,
            'used_count' => (int) $coupon->used_count ?? 0,
            'valid_from' => (string) $coupon->valid_from ?? '',
            'valid_to' => (string) $coupon->valid_to ?? '',
            'status' => (int) $coupon->status ?? 0,
            'created_at' => (string) $coupon->created_at ?? '',
            'updated_at' => (string) $coupon->updated_at ?? '',
        ];
    }

    public function applyCoupon(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'coupon_code' => 'required|string|min:2|max:255',
            ]);
            if ($validator->fails()) {
                return $this->validationRes($validator->errors());
            }

            $coupon = Coupon::where('code', $request->coupon_code)->where('status', 1)->first();
            if (!$coupon) {
                return $this->failRes('Invalid coupon code');
            }
            if ($coupon->isExpired()) {
                return $this->failRes('Coupon is expired');
            }
            if ($coupon->isNotStarted()) {
                return $this->failRes('Coupon is not started');
            }
            if ($coupon->isUsageLimitReached()) {
                return $this->failRes('Coupon usage limit reached');
            }
            if ($coupon->canBeUsed()) {
                $data = $this->field($coupon);
                return $this->successRes('Coupon applied successfully',$data);
            }
            return $this->failRes('Coupon cannot be used');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}
