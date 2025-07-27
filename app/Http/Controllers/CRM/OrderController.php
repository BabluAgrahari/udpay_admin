<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Order::query();

            // Apply filters
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            if ($request->has('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->has('order_status')) {
                $query->where('order_status', $request->order_status);
            }

            if ($request->has('date_range')) {
                $dateRange = $request->date_range;
                if (!empty($dateRange)) {
                    $dates = explode(' - ', $dateRange);
                    if (count($dates) == 2) {
                        $startDate = date('Y-m-d 00:00:00', strtotime($dates[0]));
                        $endDate = date('Y-m-d 23:59:59', strtotime($dates[1]));
                        $query->whereBetween('order_date', [$startDate, $endDate]);
                    }
                }
            }

            $data['orders'] = $query->orderBy('created', 'desc')->paginate(10);

            return view('CRM.Order.index', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
} 