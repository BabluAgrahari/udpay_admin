<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tax Invoice</title>
    <style>
        @page {
            margin: 30px 40px 80px 40px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 80px;
            background: #fff;
            border-bottom: 1px solid #ccc;
            padding: 16px 0 0 0;
            text-align: center;
        }

        .header-logo {
            float: left;
            width: 60px;
            height: 60px;
            background: #eee;
            border-radius: 8px;
            display: inline-block;
            margin-right: 16px;
            vertical-align: middle;
        }

        .header-title {
            display: inline-block;
            vertical-align: middle;
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 60px;
            background: #fff;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 13px;
            color: #888;
            padding-top: 18px;
        }

        .content {
            margin-top: 0;
        }

        h2 {
            margin-top: 40px;
        }

        .section {
            margin-bottom: 20px;
        }

        .address,
        .info,
        .item-table,
        .footer-section {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 8px;
        }

        .item-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .footer-section {
            margin-bottom: 0;
        }

        .spacer {
            height: 20px;
        }
    </style>
</head>

<body>
    {{-- <header>
    <span class="header-logo">LOGO</span>
    <span class="header-title">Unipay Pvt. Ltd. &mdash; Tax Invoice</span>
  </header> --}}
    <footer>
        &copy; {{ date('Y') }} Unipay Pvt. Ltd. &mdash; This is a system generated invoice.
    </footer>
    <div class="content" style="margin-top: 10px; margin-bottom: 30px;">
        <h1 style="text-align:center; margin-bottom: 30px;">Tax Invoice</h1>
        <div class="section address">
            <strong>Billing Address / Ship To / Bill To:</strong><br>
            {{ $order->delivery_address['name'] ?? $order->customer_name ?? 'N/A' }}<br>
            {{ $order->delivery_address['address'] ?? $order->customer_address ?? 'N/A' }}<br>
            @if(isset($order->delivery_address['city']) || isset($order->delivery_address['state']) || isset($order->delivery_address['pincode']))
                {{ $order->delivery_address['city'] ?? '' }}{{ isset($order->delivery_address['city']) && isset($order->delivery_address['state']) ? ', ' : '' }}{{ $order->delivery_address['state'] ?? '' }}{{ isset($order->delivery_address['state']) && isset($order->delivery_address['pincode']) ? ' - ' : '' }}{{ $order->delivery_address['pincode'] ?? '' }}<br>
            @endif
            Phone: {{ $order->delivery_address['phone'] ?? $order->customer_phone ?? 'N/A' }}<br>
            Email: {{ $order->delivery_address['email'] ?? $order->customer_email ?? 'N/A' }}
        </div>
        <div class="section info">
            <strong>Order Details:</strong><br>
            Order ID: {{ $order->order_number ?? 'N/A' }}<br>
            Order Date: {{ $order->order_date ? date('d-m-Y', strtotime($order->order_date)) : 'N/A' }}<br>
            Invoice Date: {{ date('d-m-Y') }}<br>
            Invoice Number: INV-{{ time() }}<br>
            Customer Name: {{ $order->customer_name ?? 'N/A' }}<br>
            Payment Method: {{ ucfirst($order->payment_method ?? 'N/A') }}<br>
            @if(isset($order->weight))
                Weight: {{ $order->weight }} GM<br>
            @endif
            @if(isset($order->value) && $order->payment_method == 'cod')
                COD Value: ₹{{ number_format($order->value, 2) }}<br>
            @endif
        </div>
        <div class="section item-table">
            <h2>Item Details</h2>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Price ₹</th>
                    <th>Total ₹</th>
                </tr>
                @php 
                    $products = is_array($order->products ?? null) ? $order->products : (json_decode($order->products, true) ?? []);
                    $total = 0;
                @endphp
                @if(!empty($products))
                    @foreach($products as $product)
                        @php $productTotal = ($product['price'] ?? 0) * ($product['quantity'] ?? 1); $total += $productTotal; @endphp
                        <tr>
                            <td>{{ $product['name'] ?? ($product['product_name'] ?? 'N/A') }}</td>
                            <td>{{ $product['quantity'] ?? 1 }}</td>
                            <td>{{ number_format($product['price'] ?? 0, 2) }}</td>
                            <td>{{ number_format($productTotal, 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align: center;">No products found</td>
                    </tr>
                @endif
            </table>
            <p><strong>Grand Total: ₹{{ number_format($total, 2) }}</strong></p>
        </div>

        <div class="section footer-section">
            <strong>Note:</strong><br>
            Please keep the invoice for warranty purposes.<br>
            The goods sold are intended for end-user consumption and not for re-sale.<br>
            @if(isset($order->dimensions))
                @php $dimensions = is_array($order->dimensions ?? null) ? $order->dimensions : (json_decode($order->dimensions, true) ?? []); @endphp
                @if(!empty($dimensions))
                    <strong>Package Dimensions:</strong><br>
                    @foreach($dimensions as $index => $dimension)
                        Package {{ $index + 1 }}: {{ $dimension['length'] ?? 0 }} × {{ $dimension['breadth'] ?? 0 }} × {{ $dimension['height'] ?? 0 }} {{ $dimension['unit'] ?? 'cm' }}<br>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</body>

</html>
