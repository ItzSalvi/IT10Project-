<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redemption Receipt #{{ $redemption->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 15px;
            background: white;
            color: #333;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #10b981;
            color: white;
            border-radius: 6px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        
        .header p {
            margin: 3px 0 0 0;
            font-size: 11px;
            opacity: 0.9;
        }
        
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 4px;
        }
        
        .receipt-info .left, .receipt-info .right {
            flex: 1;
        }
        
        .receipt-info h3 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #374151;
        }
        
        .receipt-info p {
            margin: 3px 0;
            font-size: 11px;
            color: #6b7280;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background: #374151;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .quantity-badge {
            background: #3b82f6;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .total-section {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .total-section h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #374151;
        }
        
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
            text-align: right;
        }
        
        .important-info {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .important-info h4 {
            margin: 0 0 8px 0;
            color: #92400e;
            font-size: 13px;
        }
        
        .important-info ul {
            margin: 0;
            padding-left: 15px;
        }
        
        .important-info li {
            margin: 3px 0;
            font-size: 11px;
            color: #92400e;
        }
        
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 10px;
        }
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 4px;
        }
        
        .qr-placeholder {
            width: 80px;
            height: 80px;
            background: #d1d5db;
            margin: 0 auto 8px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Redemption Receipt</h1>
        <p>Transaction #{{ $redemption->id }}</p>
        <p>{{ $redemption->created_at->format('M d, Y \a\t h:i A') }}</p>
    </div>

    <!-- Receipt Information -->
    <div class="receipt-info">
        <div class="left">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $redemption->user->full_name }}</p>
            <p><strong>Email:</strong> {{ $redemption->user->email }}</p>
        </div>
        <div class="right">
            <h3>Receipt Details</h3>
            <p><strong>Receipt ID:</strong> {{ $redemption->id }}</p>
            <p><strong>Date:</strong> {{ $redemption->created_at->format('M d, Y') }}</p>
            <p><strong>Time:</strong> {{ $redemption->created_at->format('h:i A') }}</p>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 30%;">Item Name</th>
                <th style="width: 35%;">Description</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 12%; text-align: center;">Points Each</th>
                <th style="width: 13%; text-align: right;">Total Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach($redemption->items as $item)
                <tr>
                    <td><strong>{{ $item->reward->name }}</strong></td>
                    <td>{{ $item->reward->description ?? 'No description' }}</td>
                    <td style="text-align: center;">
                        <span class="quantity-badge">{{ $item->quantity }}</span>
                    </td>
                    <td style="text-align: center;">{{ $item->reward->points_req }} pts</td>
                    <td style="text-align: right;"><strong>{{ $item->points_spent }} pts</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        <h3>Redemption Summary</h3>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="margin: 5px 0; font-size: 14px; color: #6b7280;">
                    Total Points Spent: <strong>{{ $redemption->total_points_spent }} points</strong>
                </p>
                <p style="margin: 5px 0; font-size: 14px; color: #6b7280;">
                    Remaining Points: <strong>{{ $redemption->user->total_points }} points</strong>
                </p>
            </div>
            <div class="total-amount">
                {{ $redemption->total_points_spent }} pts
            </div>
        </div>
    </div>

    <!-- Important Information -->
    <div class="important-info">
        <h4>Important Information</h4>
        <ul>
            <li>This receipt is valid for <strong>30 days</strong> from the date of redemption</li>
            <li>Present this receipt to the admin office to claim your items</li>
            <li>Receipt expires on: <strong>{{ $redemption->created_at->addDays(30)->format('M d, Y') }}</strong></li>
            <li>Keep this receipt safe as it cannot be reissued</li>
            <li>For any issues, contact the admin office with this receipt number</li>
        </ul>
    </div>

    <!-- QR Code Section -->
    <div class="qr-section">
        <div class="qr-placeholder">
            QR Code<br>
            {{ $redemption->id }}
        </div>
        <p style="margin: 0; font-size: 12px; color: #6b7280;">
            Receipt ID: <strong>{{ $redemption->id }}</strong>
        </p>
        <p style="margin: 5px 0 0 0; font-size: 11px; color: #9ca3af;">
            Scan this code at the admin office for quick verification
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Bottle Recycling Points System</strong></p>
        <p>Thank you for your environmental contribution!</p>
        <p>Generated on {{ now()->format('M d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
