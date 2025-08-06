<?php

namespace App\Http\Controllers\CRM;

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
            return view('CRM.Coupon.index');
        } catch (Exception $e) {
           abort(500, $e->getMessage());
        }
    }



    public function store(CouponRequest $request)
    {
        try {
            $save = new Coupon();
            $save->code = strtoupper($request->code);
            $save->name = $request->name;
            $save->description = $request->description;
            $save->discount_type = $request->discount_type;
            $save->discount_value = $request->discount_value;
            $save->minimum_amount = $request->minimum_amount ?? 0;
            $save->maximum_discount = $request->maximum_discount;
            $save->usage_limit = $request->usage_limit;
            $save->used_count = 0;
            $save->valid_from = $request->valid_from ? date('Y-m-d H:i:s', strtotime($request->valid_from)) : null;
            $save->valid_to = $request->valid_to ? date('Y-m-d H:i:s', strtotime($request->valid_to)) : null;
            $save->status = $request->status;

            if($save->save())
                return $this->successMsg('Coupon created successfully');

            return $this->failMsg('Coupon not Created.');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            return $this->successMsg('Coupon retrieved successfully', $coupon);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            
            // Check if it's an AJAX request
            if (request()->ajax()) {
                return $this->successMsg('Coupon retrieved successfully', $coupon);
            }
            
            return view('CRM.Coupon.edit', compact('coupon'));
        } catch (Exception $e) {
            if (request()->ajax()) {
                return $this->failMsg($e->getMessage());
            }
            return abort(500, $e->getMessage());
        }
    }

    public function update(CouponRequest $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->code = strtoupper($request->code);
            $coupon->name = $request->name;
            $coupon->description = $request->description;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount_value = $request->discount_value;
            $coupon->minimum_amount = $request->minimum_amount ?? 0;
            $coupon->maximum_discount = $request->maximum_discount;
            $coupon->usage_limit = $request->usage_limit;
            $coupon->valid_from = $request->valid_from ? date('Y-m-d H:i:s', strtotime($request->valid_from)) : null;
            $coupon->valid_to = $request->valid_to ? date('Y-m-d H:i:s', strtotime($request->valid_to)) : null;
            $coupon->status = $request->status;

           if($coupon->save())
                return $this->successMsg('Coupon updated successfully');

            return $this->failMsg('Something went wrong, Coupon not updated.');
        } catch (Exception $e) {
            return $this->errorRes($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $coupon = Coupon::findOrFail($request->id);
            $coupon->status = $request->status;
            $coupon->save();

            return $this->successMsg('Status updated successfully');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();

            return $this->successMsg('Coupon deleted successfully');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $query = Coupon::query();

        // Filtering
        if ($request->search && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $total = $query->count();

        // Ordering
        $columns = $request->columns;
        if ($request->order && count($request->order)) {
            foreach ($request->order as $order) {
                $colIdx = $order['column'];
                $colName = $columns[$colIdx]['data'];
                $dir = $order['dir'];
                $query->orderBy($colName, $dir);
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $coupons = $query->skip($start)->take($length)->get();

        $data = $coupons->map(function ($coupon) {
            $status = '';
            if ($coupon->isExpired()) {
                $status = '<span class="badge bg-danger">Expired</span>';
            } elseif ($coupon->isNotStarted()) {
                $status = '<span class="badge bg-warning">Not Started</span>';
            } elseif ($coupon->isUsageLimitReached()) {
                $status = '<span class="badge bg-secondary">Limit Reached</span>';
            } elseif ($coupon->status) {
                $status = '<span class="badge bg-success">Active</span>';
            } else {
                $status = '<span class="badge bg-danger">Inactive</span>';
            }

            return [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount_type' => ucfirst($coupon->discount_type),
                'discount_value' => $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '₹' . $coupon->discount_value,
                'usage_limit' => $coupon->usage_limit ? $coupon->used_count . '/' . $coupon->usage_limit : 'Unlimited',
                'valid_from' => $coupon->valid_from ? date('d M, Y', strtotime($coupon->valid_from)) : 'No Start Date',
                'valid_to' => $coupon->valid_to ? date('d M, Y', strtotime($coupon->valid_to)) : 'No End Date',
                'status' => $status,
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
} 