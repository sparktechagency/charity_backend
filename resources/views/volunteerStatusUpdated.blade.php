<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Status Updated</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fafafa;
            color: #444;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Container */
        .container {
            width: 100%;
            max-width: 700px;
            margin: 30px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #3498db;
            /* Side border */
            border-right: 5px solid #3498db;
            /* Side border */
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        /* Header */
        h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 30px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* Section */
        .section {
            margin-bottom: 25px;
        }

        .section p {
            font-size: 18px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 10px;
        }

        .section strong {
            color: #3498db;
        }

        /* Button Styles */
        .button {
            display: inline-block;
            padding: 12px 25px;
            border: 2px solid #3498db;
            color: #3498db;
            text-decoration: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #3498db;
            color: #fff;
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 16px;
            color: #999;
            margin-top: 30px;
        }

        .footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive Design */
        @media screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            .section p {
                font-size: 16px;
            }

            .button {
                padding: 8px 18px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Volunteer Status Updated</h2>
        <hr>
        <div class="section">
            <p><strong>Hello {{ $volunteer->name }},</strong></p>
            <p>Your volunteer status has been updated to: <strong>{{ ucfirst($status) }}</strong>.</p>
        </div>

        <div class="section">
            @if ($status == 'approved')
                <p>🎉 Congratulations! Your volunteer application has been approved. Thank you for your dedication!</p>
            @elseif($status == 'pending')
                <p>Your volunteer application is still under review. We will notify you once the status changes.</p>
            @elseif($status == 'suspended')
                <p>⚠️ Your volunteer status has been suspended. Please contact us for more information.</p>
            @endif
        </div>
        <p class="footer">Thank you for your continued support! <br> <a href="#">Visit Our Site</a></p>
    </div>
</body>

</html>
