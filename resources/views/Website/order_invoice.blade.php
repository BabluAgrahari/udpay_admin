
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
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #505050;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e9e9e9;
            padding: 20px;
        }
        
        .invoice-header {
            background: transparent;
            color: #000;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .invoice-number {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .invoice-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .section-title {
            font-weight: 600;
            color: #000;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .section-content {
            line-height: 1.5;
            font-size: 13px;
        }
        
        .order-summary {
            margin-bottom: 20px;
        }
        
        .summary-header {
            background: #f7f7f7;
            padding: 12px;
            border: 1px solid #e5e5e5;
            border-bottom: none;
        }
        
        .summary-title {
            font-weight: 600;
            font-size: 14px;
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
            font-size: 11px;
        }
        
        .product-table th {
            background: #f7f7f7;
            padding: 8px 4px;
            text-align: center;
            font-weight: 600;
            color: #000;
            border-right: 1px solid #e5e5e5;
        }
        
        .product-table th:last-child {
            border-right: none;
        }
        
        .product-table td {
            padding: 8px 4px;
            text-align: center;
            border-right: 1px solid #e5e5e5;
            border-bottom: 1px solid #e5e5e5;
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
            margin-bottom: 20px;
        }
        
        .transaction-table th {
            background: #f7f7f7;
            padding: 10px;
            text-align: center;
            font-weight: 600;
            color: #000;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .transaction-table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .invoice-footer {
            display: table;
            width: 100%;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e5e5;
        }
        
        .action-buttons {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            font-size: 12px;
            margin-right: 10px;
        }
        
        .btn-primary {
            background: #0071cc;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .signature-section {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }
        
        .signature-image {
            max-width: 120px;
            height: auto;
            margin-bottom: 8px;
        }
        
        .signature-text {
            font-weight: 600;
            color: #000;
            font-size: 12px;
        }
        
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            
            .invoice-container {
                border: none;
                margin: 0;
                padding: 0;
            }
            
            .action-buttons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container" id="receipt">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <img src="https://uni-pay.in/assets/images/logo1.jpg" width="200">
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
                        Mob :
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
                    <div style="display: table; width: 100%;">
                        <span class="summary-title" style="display: table-cell; text-align: left;">Order Summary</span>
                        <span class="order-id" style="display: table-cell; text-align: right;">UNI220248324535</span>
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
                            <td>&#8377;1,598.00</td>
                            <td>1</td>
                            <td>&#8377;500.00</td>
                            <td>&#8377;599</td>
                            <td>&#8377;846.61</td>
                            <td>0%</td>
                            <td>&#8377;0</td>
                            <td>0%</td>
                            <td>&#8377;0</td>
                            <td>18%</td>
                            <td>&#8377;152.39</td>
                            <td>&#8377;999.00</td>
                        </tr>
                        
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                            <td><strong>&#8377;1,598.00</strong></td>
                            <td><strong>1</strong></td>
                            <td><strong>&#8377;500</strong></td>
                            <td><strong>&#8377;599</strong></td>
                            <td><strong>&#8377;846.61</strong></td>
                            <td></td>
                            <td><strong>&#8377;0</strong></td>
                            <td></td>
                            <td><strong>&#8377;0</strong></td>
                            <td></td>
                            <td><strong>&#8377;152.39</strong></td>
                            <td><strong>&#8377;999</strong></td>
                        </tr>
                        
                        <tr>
                            <td colspan="13" style="text-align: right;"><strong>Shipping Charges</strong></td>
                            <td><strong>&#8377;0.00</strong></td>
                        </tr>
                        
                        <tr class="total-row">
                            <td colspan="13" style="text-align: right;"><strong>Payable Amount</strong></td>
                            <td><strong>&#8377;999.00</strong></td>
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
                        <td>&#8377;999</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Invoice Footer -->
            <div class="invoice-footer">
                
                
                <div class="signature-section">
                    <div class="signature-text">Authorized Signature</div>
                    <div style="margin-top: 20px; border-top: 2px solid #000; width: 150px; margin-left: auto; margin-right: auto;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printInvoice() {
            // Hide print buttons before printing
            const printButtons = document.getElementById('print-buttons');
            if (printButtons) {
                printButtons.style.display = 'none';
            }
            
            // Print the page
            window.print();
            
            // Show buttons again after printing
            setTimeout(() => {
                if (printButtons) {
                    printButtons.style.display = 'table-cell';
                }
            }, 1000);
        }
    </script>
</body>
</html>