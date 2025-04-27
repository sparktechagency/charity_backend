<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winner Details</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            margin: 0;
            padding: 30px;
        }

        .winner-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 50px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 650px;
            padding: 40px;
            text-align: center;
        }

        .winner-card h2 {
            color: #2c3e50;
            font-size: 30px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .congratulations-message {
            color: #27ae60;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 30px;
            background-color: #e0f7ea;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            text-align: left;
            padding: 15px;
            font-size: 16px;
            border-bottom: 1px solid #f4f4f4;
            color: #555;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        table td {
            background-color: #fefefe;
            color: #333;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .status-pending {
            background-color: #f39c12;
        }

        .status-winner {
            background-color: #27ae60;
        }

        .status-rejected {
            background-color: #e74c3c;
        }

        .icon {
            margin-right: 10px;
            font-size: 18px;
        }

        .footer-note {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }

        .auction-image-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .auction-img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="winner-card">
        <h2>🏆 Winner Details</h2>

        <!-- Congratulations Message -->
        <div class="congratulations-message">
            🎉 Congratulations, {{ $winner['name'] }}! 🎉<br> You've won the auction.
        </div>

        <table>
            <tr>
                <th><span class="icon">🎯</span>Auction Title</th>
                <td>{{ $winner->auction->title ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th><span class="icon">📝</span>Description</th>
                <td>{{ $winner->auction->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th><span class="icon">👤</span>Name</th>
                <td>{{ $winner['name'] }}</td>
            </tr>
            <tr>
                <th><span class="icon">📧</span>Email</th>
                <td>{{ $winner['email'] }}</td>
            </tr>
            <tr>
                <th><span class="icon">📞</span>Contact Number</th>
                <td>{{ $winner['contact_number'] }}</td>
            </tr>
            <tr>
                <th><span class="icon">💸</span>Bid Amount</th>
                <td>${{ number_format($winner['bit_online'], 2) }}</td>
            </tr>
            <tr>
                <th><span class="icon">📅</span>Date</th>
                <td>{{ \Carbon\Carbon::parse($winner['created_at'])->format('F j, Y') }}</td>
            </tr>
            <tr>
                <th><span class="icon">✅</span>Status</th>
                <td>
                    <span class="status-badge status-{{ strtolower($winner['status']) }}">
                        {{ ucfirst($winner['status']) }}
                    </span>
                </td>
            </tr>
        </table>

        <div class="footer-note">
            This is an auto-generated message based on the auction results.
        </div>
    </div>
</body>

</html>
