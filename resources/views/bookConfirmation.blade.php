<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Booking Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-body {
            padding: 30px;
        }

        .email-body h2 {
            color: #333333;
        }

        .booking-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .booking-details th,
        .booking-details td {
            border: 1px solid #dddddd;
            padding: 12px;
            text-align: left;
        }

        .booking-details th {
            background-color: #f2f2f2;
        }

        .email-footer {
            background-color: #f9f9f9;
            color: #777777;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Booking Status Update</h1>
        </div>
        <div class="email-body">
            <h2>Hello {{ $book['name'] }},</h2>
            <p>Your booking status has been updated to: <strong>{{ $book['book_status'] }}</strong>.</p>
            <p><strong>Booking Details:</strong></p>
            <table class="booking-details">
                <tr>
                    <th>Date</th>
                    <td>{{ $book['book_date'] }}</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td>{{ $book['book_time'] }}</td>
                </tr>
                <tr>
                    <th>Telephone</th>
                    <td>{{ $book['telephone_number'] }}</td>
                </tr>
            </table>
            <p>Thank you for choosing our service.</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Your Company Name. All rights reserved.
        </div>
    </div>
</body>

</html>
