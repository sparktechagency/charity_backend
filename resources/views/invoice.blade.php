<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction['invoice'] }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            margin: 0;
            padding: 30px;
        }

        .invoice-container {
            max-width: 800px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .invoice-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .invoice-title h1 {
            font-size: 2rem;
            color: #1d4ed8;
        }

        .invoice-title p {
            font-size: 1rem;
            color: #6b7280;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 16px 12px;
            text-align: left;
        }

        th {
            background-color: #1d4ed8;
            color: white;
            font-weight: 600;
        }

        td {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }

        .amount {
            font-size: 1.3rem;
            font-weight: bold;
            color: #22c55e;
        }

        .status.paid {
            color: #16a34a;
            font-weight: bold;
        }

        .status.pending {
            color: #f59e0b;
            font-weight: bold;
        }

        .status.failed {
            color: #ef4444;
            font-weight: bold;
        }

        footer {
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .footer-link {
            color: #1d4ed8;
            text-decoration: none;
        }

        .footer-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-title">
            <h1>Invoice #{{ $transaction['invoice'] }}</h1>
            <p>Date: {{ \Carbon\Carbon::parse($transaction['created_at'])->format('F d, Y') }}</p>
        </div>

        <table>
            <tr>
                <th>Name</th>
                <td>{{ $transaction['name'] }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $transaction['email'] }}</td>
            </tr>
            <tr>
                <th>Remark</th>
                <td>{{ $transaction['remark'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Donation Type</th>
                <td>{{ ucfirst($transaction['donation_type']) }}</td>
            </tr>
            <tr>
                <th>Payment Type</th>
                <td>{{ ucfirst($transaction['payment_type']) }}</td>
            </tr>
            <tr>
                <th>Frequency</th>
                <td>{{ ucfirst($transaction['frequency'] ?? 'One-time') }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td class="amount">£ {{ number_format($transaction['amount'], 2) }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td class="status {{ strtolower($transaction['payment_status']) }}">
                    {{ ucfirst($transaction['payment_status']) }}
                </td>
            </tr>
        </table>

        <footer>
            Need help? <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="footer-link">Contact support</a>
        </footer>
    </div>
</body>

</html>
