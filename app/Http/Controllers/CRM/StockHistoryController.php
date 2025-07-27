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
            $data['products'] = Product::status()->get();

            return view('CRM.Stock.history', $data);
        } catch (Exception $e) {
           abort(500, $e->getMessage());
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

    public function datatable(Request $request)
    {
        $query = Stock::with(['product', 'productVariant', 'user', 'unit', 'order']);

        // Filtering
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('product_name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                })
                ->orWhere('remarks', 'like', "%{$search}%");
            });
        }
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->date_range) {
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

        $total = $query->count();

        // Ordering
        $columns = $request->columns;
        if ($request->order && count($request->order)) {
            foreach ($request->order as $order) {
                $colIdx = $order['column'];
                $colName = $columns[$colIdx]['data'];
                $dir = $order['dir'];
                if ($colName !== 'actions') {
                    if ($colName === 'date_time') {
                        $query->orderBy('created', $dir);
                    } else {
                        $query->orderBy($colName, $dir);
                    }
                }
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $stocks = $query->skip($start)->take($length)->get();

        $data = $stocks->map(function ($stock) {
            return [
                'date_time' => '<div><strong>' . $stock->dFormat($stock->created) . '</strong><br><small class="text-muted">' . date('H:i:s', $stock->created) . '</small></div>',
                'product' => '<div><strong>' . ($stock->product->product_name ?? 'N/A') . '</strong><br><small class="text-muted">SKU: ' . ($stock->product->sku ?? 'N/A') . '</small></div>',
                'variant' => $stock->productVariant ? '<span class="badge bg-info">' . (is_array($stock->productVariant->attributes) ? json_encode($stock->productVariant->attributes) : $stock->productVariant->attributes) . '</span>' : '<span class="text-muted">-</span>',
                'type' => $stock->type === 'up' ? '<span class="badge bg-success"><i class="bx bx-up-arrow-alt"></i> Up</span>' : '<span class="badge bg-danger"><i class="bx bx-down-arrow-alt"></i> Down</span>',
                'quantity' => '<strong>' . number_format($stock->stock, 2) . '</strong>',
                'unit' => $stock->unit->unit ?? 'N/A',
                'closing_stock' => '<strong>' . number_format($stock->closing_stock, 2) . '</strong>',
                'user' => $stock->user->name ?? 'N/A',
                'order' => $stock->order ? '<a href="javascript:void(0);" class="text-primary">' . ($stock->order->order_number ?? 'N/A') . '</a>' : '<span class="text-muted">-</span>',
                'remarks' => $stock->remarks ? '<span title="' . $stock->remarks . '">' . \Str::limit($stock->remarks, 30) . '</span>' : '<span class="text-muted">-</span>',
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