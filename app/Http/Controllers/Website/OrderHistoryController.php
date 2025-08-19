<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderToProduct;
use App\Models\UserAddress;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\PaymentGatway\CashFree;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderHistoryController extends Controller
{

    public function index(Request $request)
    {
        try {
            $data['orders'] = Order::with([ 'orderToProducts', 'orderToProducts.product', 'orderToProducts.variant']   )->where('uid', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('Website.order_history', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function orderDetail($id)
    {
        try {
            $data['order'] = Order::with([  'orderToProducts', 'orderToProducts.product', 'orderToProducts.variant'])->where('id', $id)->first();
            return view('Website.order_detail', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }


    public function invoice($id)
    {
        // try {
            // Load order with all necessary relationships for invoice
            $data['order'] = Order::with([
                'orderToProducts', 
                'orderToProducts.product', 
                'orderToProducts.variant',
                'user',
                'shipping_address'
            ])->where('id', $id)->first();
            
            if (!$data['order']) {
                abort(404, 'Order not found');
            }
            
            // Generate PDF using the updated template
            $pdf = Pdf::loadView('Website.order_invoice', compact('data'));
            
            // Set PDF options for better compatibility
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            
            $filename = 'invoice_' . ($data['order']->order_number ?? $data['order']->id) . '.pdf';
            return $pdf->download($filename);

        // } catch (\Exception $e) {
        //     \Log::error('Error generating invoice: ' . $e->getMessage(), [
        //         'order_id' => $id,
        //         'trace' => $e->getTraceAsString()
        //     ]);
        //     abort(500, 'Error generating invoice: ' . $e->getMessage());
        // }
    }




}