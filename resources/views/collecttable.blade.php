<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Donate Art, Antique or Collectables</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f2f2f2;">

    <table cellpadding="0" cellspacing="0" width="100%" style="padding: 20px;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" width="700"
                    style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#2575fc" style="padding: 30px;">
                            <h2 style="margin: 0; font-size: 26px; color: #ffffff;">Donate Art, Antique or Collectables
                            </h2>
                        </td>
                    </tr>

                    <!-- User Info -->
                    <tr>
                        <td style="padding: 30px;">
                            <table cellpadding="0" cellspacing="0" width="100%" style="font-size: 16px; color: #333;">
                                <tr>
                                    <td style="padding-bottom: 10px;"><strong>Name:</strong> {{ $sender_data['name'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px;"><strong>Email:</strong>
                                        {{ $sender_data['email'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px;"><strong>Item Name:</strong>
                                        {{ $sender_data['item_name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px;"><strong>Description:</strong>
                                        {{ $sender_data['description'] }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Image Section -->
                    @if (!empty($sender_data['images']))
                        <tr>
                            <td style="padding: 0 30px 30px 30px;">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="padding-bottom: 15px; font-size: 16px; color: #2575fc;">
                                            <strong>Uploaded Images:</strong></td>
                                    </tr>
                                    @foreach ($sender_data['images'] as $image)
                                        <tr>
                                            <td style="padding-bottom: 20px;">
                                                <table cellpadding="0" cellspacing="0" width="100%"
                                                    style="border: 1px solid #e0e0e0; border-radius: 8px;">
                                                    <tr>
                                                        <td align="center" style="padding: 10px;">
                                                            <img src="{{ $image }}" alt="Uploaded Image"
                                                                style="max-width: 100%; border-radius: 6px;" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="padding: 10px;">
                                                            <a href="{{ $image }}" target="_blank"
                                                                style="background-color: #2575fc; color: #fff; text-decoration: none; padding: 8px 18px; border-radius: 25px; font-size: 14px;">View
                                                                Full Image</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endif

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 20px; font-size: 14px; color: #999;">
                            Thank you for your generous donation.<br>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
