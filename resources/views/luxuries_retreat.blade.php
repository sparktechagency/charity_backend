<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxurious Retreat Donation</title>
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

        /* Image Styles */
        img {
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            margin: 15px 0;
            transition: transform 0.3s ease;
        }

        img:hover {
            transform: scale(1.05);
        }

        /* Button for View Image */
        .view-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .view-button:hover {
            background-color: #2980b9;
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

            .view-button {
                padding: 8px 18px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Donation Details</h2>
        <hr>
        <div class="section">
            <p><strong>Name:</strong> {{ $sender_data['name'] }}</p>
            <p><strong>Email:</strong> {{ $sender_data['email'] }}</p>
            <p><strong>Item Name:</strong> {{ $sender_data['item_name'] }}</p>
            <p><strong>Description:</strong> {{ $sender_data['description'] }}</p>
        </div>

        @if (!empty($sender_data['images']))
            <div class="section">
                <p><strong>Images </strong>
                <ul>
                    @foreach ($sender_data['images'] as $image)
                        <br>
                        <a href="{{ $image }}" target="_blank" class="view-button">View Image</a>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="footer">Thank you for your Luxurious contribution! <br> <a href="">Visit Our Site</a></p>
    </div>
</body>

</html>
