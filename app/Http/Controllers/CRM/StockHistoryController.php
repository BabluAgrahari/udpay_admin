<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StockHistoryController extends Controller
{
    /**
     * Display stock history listing
     */
    public function index(Request $request)
    {
        try {
            $query = Stock::with(['product', 'productVariant', 'user', 'unit', 'order']);

            // Apply filters
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('product_name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    })
                    ->orWhere('remarks', 'like', "%{$search}%");
                });
            }

            if ($request->has('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            if ($request->has('date_range')) {
                $dateRange = $request->date_range;
                if (!empty($dateRange)) {
                    $dates = explode(' - ', $dateRange);
                    if (count($dates) == 2) {
                        $startDate = date('Y-m-d 00:00:00', strtotime($dates[0]));
                        $endDate = date('Y-m-d 23:59:59', strtotime($dates[1]));
                        $query->whereBetween('created', [$startDate, $endDate]);
                    }
                }
            }

            $data['stockHistory'] = $query->orderBy('created', 'desc')->paginate(20);
            $data['products'] = Product::status()->get();

            return view('CRM.Stock.history', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function create()
    {
        $products = Product::status()->get();
        $units = Unit::status()->get();
        return view('CRM.Stock.create', compact('products', 'units'));
    }

    public function store(Request $request)
    {
        try {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,_id',
            'stock' => 'required|numeric|min:0.01',
            'unit_id' => 'required|exists:units,_id',
            'product_variant_id' => 'nullable|exists:product_variants,_id',
        ]);

        if ($validator->fails()) {
            return $this->failMsg($validator->errors()->first());
        }

        $stock = stockUpdate([
            'product_id' => $request->product_id,
            'product_variant_id' => $request->product_variant_id??'',
            'stock' => $request->stock,
            'type' => 'up',
            'user_id' => Auth::user()->_id ?? null,
            'unit_id' => $request->unit_id,
            'remarks' => $request->remarks??'',
            'source' => 'manual',
        ]);

        if($stock['success']){
            return $this->successMsg('Stock added successfully.');
        }
        return $this->failMsg('Stock not added');

        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
} 