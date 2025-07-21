<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PickupAddress;

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

    public function store(){

        $products = Product::limit(10)->get();
        $order_details = [];
        foreach($products as $product){
            $order_details[] = [
                'name' => $product->product_name,
                'product_id' => $product->_id,
                'quantity' => 1,
                'price' => $product->sale_price,
                'total' => $product->sale_price,
            ];
        }
        $order = new Order();
        $order->order_number = 'ORD-'.time();
        $order->order_date = time();
        $order->total_amount = 100;
        $order->tax_amount = 10;
        $order->discount_amount = 10;
        $order->final_amount = 100;
        $order->payment_status = 'pending';
        $order->order_status = 'pending';
        $order->payment_method = 'cod';
        $order->delivery_address = [
            'name' => 'Demo customer',
            'phone' => '01717171717',
            'address' => 'delhi, india',
            'city' => 'delhi',
            'state' => 'delhi',
            'pincode' => '1200',
            'country' => 'india',
        ];
        $order->products = (object)$order_details;
        $order->save();

        return $this->successMsg('Order created successfully');
    }

    public function insertTestData()
    {
        try {
            $totalOrders = 500000; // 5 lakh orders
            $batchSize = 1000; // Process in batches of 1000
            $batches = ceil($totalOrders / $batchSize);
            
            $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
            $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
            $paymentMethods = ['cod', 'prepaid'];
            
            $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Chennai', 'Kolkata', 'Pune', 'Ahmedabad', 'Jaipur', 'Surat'];
            $states = ['Maharashtra', 'Delhi', 'Karnataka', 'Telangana', 'Tamil Nadu', 'West Bengal', 'Gujarat', 'Rajasthan'];
            
            $startTime = microtime(true);
            
            for ($batch = 0; $batch < $batches; $batch++) {
                $orders = [];
                
                for ($i = 0; $i < $batchSize; $i++) {
                    $orderNumber = $batch * $batchSize + $i + 1;
                    $city = $cities[array_rand($cities)];
                    $state = $states[array_rand($states)];
                    
                    $orders[] = [
                        'order_number' => 'ORD-' . str_pad($orderNumber, 8, '0', STR_PAD_LEFT),
                        'customer_name' => 'Customer ' . $orderNumber,
                        'customer_email' => 'customer' . $orderNumber . '@example.com',
                        'customer_phone' => '9' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                        'customer_address' => 'Address ' . $orderNumber,
                        'order_date' => time() - rand(0, 365 * 24 * 60 * 60), // Random date within last year
                        'total_amount' => rand(100, 10000),
                        'tax_amount' => rand(10, 500),
                        'discount_amount' => rand(0, 200),
                        'final_amount' => rand(100, 10000),
                        'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                        'order_status' => $orderStatuses[array_rand($orderStatuses)],
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'delivery_address' => [
                            'name' => 'Customer ' . $orderNumber,
                            'phone' => '9' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                            'address' => 'Address ' . $orderNumber,
                            'city' => $city,
                            'state' => $state,
                            'pincode' => str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                            'country' => 'India',
                        ],
                        'products' => [
                            [
                                'name' => 'Product ' . rand(1, 100),
                                'product_id' => 'prod_' . rand(1000, 9999),
                                'quantity' => rand(1, 5),
                                'price' => rand(50, 1000),
                                'total' => rand(50, 1000),
                            ]
                        ],
                        'created' => time(),
                        'updated' => time(),
                    ];
                }
                
                // Insert batch
                Order::insert($orders);
                
                // Progress update every 10 batches
                if ($batch % 10 == 0) {
                    $progress = round(($batch / $batches) * 100, 2);
                    $elapsed = microtime(true) - $startTime;
                    $estimated = ($elapsed / ($batch + 1)) * $batches;
                    $remaining = $estimated - $elapsed;
                    
                    echo "Progress: {$progress}% | Batch: {$batch}/{$batches} | Elapsed: " . round($elapsed, 2) . "s | Remaining: " . round($remaining, 2) . "s\n";
                }
            }
            
            $totalTime = microtime(true) - $startTime;
            
            return response()->json([
                'status' => true,
                'msg' => "Successfully inserted {$totalOrders} test orders in " . round($totalTime, 2) . " seconds",
                'total_orders' => $totalOrders,
                'execution_time' => round($totalTime, 2)
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Error inserting test data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ship($id){
        try {
            $pickup_addresses = PickupAddress::where('type', 'pickup_address')->get();
            $return_addresses = PickupAddress::where('type', 'return_address')->get();
            $rto_addresses = PickupAddress::where('type', 'rto_address')->get();
            $order = Order::find($id);
            return view('CRM.Order.ship', compact('order', 'pickup_addresses', 'return_addresses', 'rto_addresses'));
        } catch (Exception $th) {
            return abort(500, $th->getMessage());
        }   
        
    }

    public function saveShipment(Request $request)
    {
        $rules = [
            'order_no' => 'required|exists:orders,order_number',
            'payment_mode' => 'required|in:cod,prepaid',
            'value' => 'required_if:payment_mode,cod|nullable|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'pickup_address_id' => 'required|exists:pickup_addresses,_id',
            'return_address_id' => 'required_if:same_address,false|exists:pickup_addresses,_id',
            'rto_address_id' => 'required_if:same_address,false|exists:pickup_addresses,_id',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
            'dimensions' => 'required|array|min:1',
            'dimensions.*.length' => 'required|numeric|min:1',
            'dimensions.*.breadth' => 'required|numeric|min:1',
            'dimensions.*.height' => 'required|numeric|min:1',
            'dimensions.*.unit' => 'required|string',
        ];
        $messages = [
            'order_no.exists' => 'Order number not found.',
            'value.required_if' => 'Value is required for COD payment mode.',
            'products.required' => 'At least one product is required.',
            'products.*.name.required' => 'Product name is required.',
            'products.*.price.required' => 'Product price is required.',
            'products.*.price.numeric' => 'Product price must be a number.',
            'products.*.price.min' => 'Product price must be at least 0.',
            'products.*.quantity.required' => 'Product quantity is required.',
            'products.*.quantity.integer' => 'Product quantity must be a whole number.',
            'products.*.quantity.min' => 'Product quantity must be at least 1.',
            'dimensions.required' => 'At least one dimension is required.',
            'dimensions.*.length.required' => 'Length is required.',
            'dimensions.*.length.numeric' => 'Length must be a number.',
            'dimensions.*.length.min' => 'Length must be at least 1.',
            'dimensions.*.breadth.required' => 'Breadth is required.',
            'dimensions.*.breadth.numeric' => 'Breadth must be a number.',
            'dimensions.*.breadth.min' => 'Breadth must be at least 1.',
            'dimensions.*.height.required' => 'Height is required.',
            'dimensions.*.height.numeric' => 'Height must be a number.',
            'dimensions.*.height.min' => 'Height must be at least 1.',
            'return_address_id.required_if' => 'Return address is required.',
            'rto_address_id.required_if' => 'RTO address is required.',
        ];
        $validated = $request->validate($rules, $messages);
        $order = Order::where('order_number', $request->order_no)->first();
        if (!$order) {
            return response()->json(['status' => false, 'msg' => 'Order not found.'], 400);
        }
        $order->payment_method = $request->payment_mode;
        if ($request->payment_mode === 'cod') {
            $order->cod_value = $request->value;
        }
        $order->weight = $request->weight;
        $order->pickup_address_id = $request->pickup_address_id;
        if($request->same_address){
            $order->return_address_id = $request->pickup_address_id;
            $order->rto_address_id = $request->pickup_address_id;
        }else{
            $order->return_address_id = $request->return_address_id;
            $order->rto_address_id = $request->rto_address_id;
        }
        $order->dimensions =$request->dimensions;
        if($order->save()){
            return $this->successMsg('Shipment details saved successfully.');
        }
        return $this->failMsg('Failed to save shipment details.');
    }

    public function downloadInvoice($id)
    {
        $order = Order::findOrFail($id);
        // You may want to pass more data as needed
        $pdf = Pdf::loadView('CRM.Order.invoice', compact('order'));
        $filename = 'invoice_' . ($order->order_number ?? $order->id) . '.pdf';
        return $pdf->download($filename);
    }

    public function pushToCourier($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            // Check if order is ready to be pushed to courier
            if (!$order->pickup_address_id || !$order->return_address_id || !$order->rto_address_id) {
                return $this->failMsg('Order must be shipped first before pushing to courier.');
            }
            
            if (!$order->weight || !$order->dimensions) {
                return $this->failMsg('Order weight and dimensions are required before pushing to courier.');
            }
            
            // Update order status to indicate it's been pushed to courier
            $order->order_status = 'processing';
            $order->pushed_to_courier = true;
            $order->pushed_to_courier_at = now();
            
            if ($order->save()) {
                // Here you can add integration with courier APIs
                // For example: Delhivery, BlueDart, etc.
                
                return $this->successMsg('Order successfully pushed to courier.');
            }
            
            return $this->failMsg('Failed to push order to courier.');
            
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $query = Order::query();

        // Filtering
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->order_status) {
            $query->where('order_status', $request->order_status);
        }

        $total = $query->count();

        // Ordering
        $columns = $request->columns;
        if ($request->order && count($request->order)) {
            foreach ($request->order as $order) {
                $colIdx = $order['column'];
                $colName = $columns[$colIdx]['data'];
                $dir = $order['dir'];
                if ($colName !== 'index' && $colName !== 'actions') {
                    $query->orderBy($colName, $dir);
                }
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $orders = $query->skip($start)->take($length)->get();

        $data = $orders->map(function ($order, $key) use ($start) {
            $customerInfo = '<div>' .
                '<strong>' . ($order->delivery_address['name'] ?? 'N/A') . '</strong><br>' .
                '<small class="text-muted">' . ($order->delivery_address['phone'] ?? 'N/A') . '</small><br>' .
                '<small class="text-muted">' . ($order->delivery_address['address'] ?? 'N/A') . ', ' . ($order->delivery_address['city'] ?? 'N/A') . ', ' . ($order->delivery_address['state'] ?? 'N/A') . ', ' . ($order->delivery_address['pincode'] ?? 'N/A') . '</small>' .
                
                '</div>';

            $amountInfo = '<strong>₹' . number_format($order->final_amount ?? 0, 2) . '</strong><br>' .
                '<small class="text-muted">Tax: ₹' . number_format($order->tax_amount ?? 0, 2) . '</small><br>' .
                '<small class="text-muted">Discount: ₹' . number_format($order->discount_amount ?? 0, 2) . '</small>';

            $paymentStatusBadge = $this->getPaymentStatusBadge($order->payment_status);
            $orderStatusBadge = $this->getOrderStatusBadge($order->order_status);

            $paymentInfo = $paymentStatusBadge . '<br>' .
                '<small class="text-muted">' . ucfirst($order->payment_method ?? 'N/A') . '</small>';

            $actions = '<div class="dropdown">' .
                '<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                '<i class="bx bx-dots-vertical-rounded"></i>' .
                '</button>' .
                '<ul class="dropdown-menu">' .
                '<li><a class="dropdown-item" href="' . url('crm/orders/ship/' . $order->_id) . '"><i class="bx bx-ship me-2"></i>Ship</a></li>' .
                '<li><a class="dropdown-item" href="' . url('crm/orders/invoice/' . $order->_id) . '"><i class="bx bx-file me-2"></i>Invoice</a></li>' .
                '<li><a class="dropdown-item push-to-courier" href="javascript:void(0);" data-id="' . $order->_id . '"><i class="bx bx-send me-2"></i>Push to Courier</a></li>' .
                '<li><hr class="dropdown-divider"></li>' .
                '<li><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-show me-2"></i>View</a></li>' .
                '</ul>' .
                '</div>';

            return [
                'index' => $start + $key + 1,
                'order_number' => '<strong>' . ($order->order_number ?? 'N/A') . '</strong>',
                'customer' => $customerInfo,
                'order_date' => $order->order_date ? date('d-m-Y', $order->order_date) : 'N/A',
                'amount' => $amountInfo,
                'payment_info' => $paymentInfo,
                'order_status' => $orderStatusBadge,
                'actions' => $actions,
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    private function getPaymentStatusBadge($status)
    {
        $badgeClass = 'bg-secondary';
        $status = $status ?? 'unknown';
        
        switch($status) {
            case 'paid':
                $badgeClass = 'bg-success';
                break;
            case 'pending':
                $badgeClass = 'bg-warning';
                break;
            case 'failed':
                $badgeClass = 'bg-danger';
                break;
            case 'refunded':
                $badgeClass = 'bg-info';
                break;
        }
        
        return '<span class="badge ' . $badgeClass . '">' . ucfirst($status) . '</span>';
    }

    private function getOrderStatusBadge($status)
    {
        $badgeClass = 'bg-secondary';
        $status = $status ?? 'unknown';
        
        switch($status) {
            case 'delivered':
                $badgeClass = 'bg-success';
                break;
            case 'shipped':
                $badgeClass = 'bg-info';
                break;
            case 'processing':
                $badgeClass = 'bg-primary';
                break;
            case 'pending':
                $badgeClass = 'bg-warning';
                break;
            case 'cancelled':
                $badgeClass = 'bg-danger';
                break;
        }
        
        return '<span class="badge ' . $badgeClass . '">' . ucfirst($status) . '</span>';
    }
} 