
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice - UniPay</title>
    <meta name="description" content="UniPay - Unique Way to Pay">
    <meta name="author" content="harnishdesign.net">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Poppins", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #505050;
            background: #f5f5f5;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #0071cc 0%, #0056a3 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .invoice-section {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-weight: 600;
            color: #000;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .section-content {
            line-height: 1.6;
        }
        
        .order-summary {
            margin-bottom: 30px;
        }
        
        .summary-header {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 6px 6px 0 0;
            border: 1px solid #e5e5e5;
            border-bottom: none;
        }
        
        .summary-title {
            font-weight: 600;
            font-size: 16px;
            color: #000;
        }
        
        .order-id {
            text-align: right;
            font-weight: 600;
            color: #0071cc;
        }
        
        .product-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e5e5;
            border-radius: 0 0 6px 6px;
            overflow: hidden;
        }
        
        .product-table th {
            background: #f7f7f7;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            color: #000;
            border-right: 1px solid #e5e5e5;
            font-size: 12px;
        }
        
        .product-table th:last-child {
            border-right: none;
        }
        
        .product-table td {
            padding: 12px 8px;
            text-align: center;
            border-right: 1px solid #e5e5e5;
            border-bottom: 1px solid #e5e5e5;
            font-size: 12px;
        }
        
        .product-table td:last-child {
            border-right: none;
        }
        
        .product-name {
            text-align: left;
            font-weight: 500;
        }
        
        .total-row {
            background: #f9f9f9;
            font-weight: 600;
        }
        
        .total-row td {
            border-top: 2px solid #0071cc;
        }
        
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .transaction-table th {
            background: #f7f7f7;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            color: #000;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .transaction-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .invoice-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #0071cc;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056a3;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-image {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .signature-text {
            font-weight: 600;
            color: #000;
        }
        
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                border: none;
                margin: 0;
            }
            
            .action-buttons {
                display: none;
            }
            
            @page {
                size: A4 portrait;
                margin: 15mm;
            }
        }
        
        @media (max-width: 768px) {
            .invoice-details {
                flex-direction: column;
            }
            
            .invoice-footer {
                flex-direction: column;
                gap: 20px;
                align-items: center;
            }
            
            .product-table {
                font-size: 11px;
            }
            
            .product-table th,
            .product-table td {
                padding: 8px 4px;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container" id="receipt">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <img src="{{ asset('front_assets/images/logo1.jpg') }}" alt="UniPay Logo" class="logo">
            <div class="invoice-title">Invoice</div>
            <div class="invoice-number">Invoice Number: IN-UNI2025-1543</div>
        </div>
        
        <!-- Invoice Body -->
        <div class="invoice-body">
            <!-- Invoice Details -->
            <div class="invoice-details">
                <div class="invoice-section">
                    <div class="section-title">Invoice To</div>
                    <div class="section-content">
                        UNI39368632 (Surendra Kumar Yadav)<br>
                        <br>
                        , -<br>
                        <br>
                        Mob: 
                    </div>
                </div>
                
                <div class="invoice-section">
                    <div class="section-title">Pay To</div>
                    <div class="section-content">
                        <strong>Uni Pay Digital Pvt. Ltd.</strong><br>
                        Office Number 2 First Floor Ganga Tower<br>
                        Khatipura Road, near S D Aggarwal<br>
                        Jhotwara Jaipur, Rajasthan 302012<br>
                        GST: 08AACCU8491L12M
                    </div>
                </div>
            </div>
            
            <!-- Payment Details -->
            <div class="invoice-details">
                <div class="invoice-section">
                    <div class="section-title">Payment Method</div>
                    <div class="section-content">Wallet</div>
                </div>
                
                <div class="invoice-section">
                    <div class="section-title">Order Date</div>
                    <div class="section-content">24-May-2025</div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="summary-title">Order Summary</span>
                        <span class="order-id">UNI220248324535</span>
                    </div>
                </div>
                
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Detail</th>
                            <th>HSN/SAC</th>
                            <th>MRP</th>
                            <th>QTY</th>
                            <th>AP</th>
                            <th>Discount</th>
                            <th>Taxable Amount</th>
                            <th>CGST Rate</th>
                            <th>CGST Amount</th>
                            <th>SGST Rate</th>
                            <th>SGST Amount</th>
                            <th>IGST Rate</th>
                            <th>IGST Amount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="product-name">ACV (Apple & Orange)</td>
                            <td>21069099</td>
                            <td>₹1,598.00</td>
                            <td>1</td>
                            <td>₹500.00</td>
                            <td>₹599</td>
                            <td>₹846.61</td>
                            <td>0%</td>
                            <td>₹0</td>
                            <td>0%</td>
                            <td>₹0</td>
                            <td>18%</td>
                            <td>₹152.39</td>
                            <td>₹999.00</td>
                        </tr>
                        
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                            <td><strong>₹1,598.00</strong></td>
                            <td><strong>1</strong></td>
                            <td><strong>₹500</strong></td>
                            <td><strong>₹599</strong></td>
                            <td><strong>₹846.61</strong></td>
                            <td></td>
                            <td><strong>₹0</strong></td>
                            <td></td>
                            <td><strong>₹0</strong></td>
                            <td></td>
                            <td><strong>₹152.39</strong></td>
                            <td><strong>₹999</strong></td>
                        </tr>
                        
                        <tr>
                            <td colspan="13" style="text-align: right;"><strong>Shipping Charges</strong></td>
                            <td><strong>₹0.00</strong></td>
                        </tr>
                        
                        <tr class="total-row">
                            <td colspan="13" style="text-align: right;"><strong>Payable Amount</strong></td>
                            <td><strong>₹999.00</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Transaction Details -->
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Gateway</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>24-May-2025</td>
                        <td>Wallet</td>
                        <td>UNI220248324535</td>
                        <td>₹999.00</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Invoice Footer -->
            <div class="invoice-footer">
                <div class="action-buttons" id="print-buttons">
                    <button class="btn btn-primary" onclick="printInvoice()">Print Invoice</button>
                    <a href="{{ url('/') }}" class="btn btn-secondary">Back to My Account</a>
                </div>
                
                <div class="signature-section">
                    <img src="{{ asset('front_assets/images/sign3.jpeg') }}" alt="Authorized Signature" class="signature-image">
                    <div class="signature-text">Authorized Signature</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printInvoice() {
            // Hide print buttons before printing
            const printButtons = document.getElementById('print-buttons');
            printButtons.style.display = 'none';
            
            // Print the page
            window.print();
            
            // Show buttons again after printing
            setTimeout(() => {
                printButtons.style.display = 'flex';
            }, 1000);
        }
        
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     printInvoice();
        // };
    </script>
</body>
</html>