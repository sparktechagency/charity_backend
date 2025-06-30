<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Auction Winner Notification</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .container {
            background: #fff;
            max-width: 650px;
            margin: auto;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.06);
        }

        h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .highlight {
            background-color: #eafaf1;
            color: #27ae60;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 30px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: left;
            font-size: 15px;
            vertical-align: top;
        }

        th {
            color: #444;
            width: 180px;
            white-space: nowrap;
        }

        td {
            color: #333;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
        }

        .status-winner {
            background-color: #27ae60;
        }

        .status-pending {
            background-color: #f39c12;
        }

        .status-rejected {
            background-color: #e74c3c;
        }

        .footer {
            font-size: 13px;
            color: #777;
            text-align: center;
            margin-top: 25px;
        }

        .icon {
            margin-right: 8px;
        }

        .btn-link {
            display: inline-block;
            background: #27ae60;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-link:hover {
            background-color: #219150;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🏆 Winner Details</h2>
        <div class="highlight">
            🎉 Congratulations, {{ $data['name'] ?? 'N/A' }}! You've won the auction.
        </div>

        <table>
            <tr>
                <th><span class="icon">🎯</span>Auction Title</th>
                <td>{{ $data['title'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th><span class="icon">📝</span>Description</th>
                <td>{{ $data['description'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th><span class="icon">👤</span>Name</th>
                <td>{{ $data['name'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th><span class="icon">📧</span>Email</th>
                <td>{{ $data['email'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th><span class="icon">📞</span>Contact Number</th>
                <td>{{ $data['contact_number'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th><span class="icon">💸</span>Bid Amount</th>
                <td>£{{ number_format($data['amount'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th><span class="icon">📅</span>Date</th>
                <td>{{ \Carbon\Carbon::parse($data['date'])->format('F j, Y') }}</td>
            </tr>
            <tr>
                <th><span class="icon">✅</span>Status</th>
                <td>
                    <span class="badge status-{{ strtolower($data['status'] ?? 'pending') }}">
                        {{ ucfirst($data['status'] ?? 'Pending') }}
                    </span>
                </td>
            </tr>
            <tr>
                <th><span class="icon">💳</span>Payment Link</th>
                <td>
                    <a href="{{ $data['link'] }}" class="btn-link">Complete Your Payment</a>
                </td>
            </tr>
        </table>

        <div class="footer">
            This message was automatically generated based on auction results.<br>
            Please complete your payment to confirm your winning status.
        </div>
    </div>
</body>

</html>
