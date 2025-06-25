<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Invoice - {{ $transaction['invoice'] }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #fefefe;
      margin: 0;
      padding: 30px;
      color: #1f2937;
    }

    .invoice-card {
      max-width: 820px;
      margin: auto;
      border: 3px solid #3c2f27; /* FULL BORDER */
      border-radius: 16px;
      background: #ffffff;
      overflow: hidden;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
    }

    .invoice-header {
      background-color: #3c2f27;
      color: #ffffff;
      padding: 30px 40px;
    }

    .invoice-header h1 {
      font-size: 1.9rem;
      margin: 0;
    }

    .invoice-header span {
      font-size: 0.95rem;
      color: #e5e7eb;
    }

    .invoice-body {
      padding: 40px;
    }

    .section {
      margin-bottom: 22px;
    }

    .section label {
      display: block;
      font-weight: 600;
      margin-bottom: 5px;
      font-size: 0.9rem;
      color: #6b7280;
    }

    .section p {
      margin: 0;
      font-size: 1rem;
      color: #1f2937;
    }

    .highlight-box {
      background: #f9fafb;
      border-left: 6px solid #3c2f27;
      padding: 20px;
      border-radius: 8px;
      margin-top: 30px;
    }

    .amount {
      font-size: 1.6rem;
      font-weight: bold;
      color: #15803d;
    }

    .status {
      margin-top: 10px;
      font-size: 1rem;
      font-weight: bold;
      padding: 6px 14px;
      border-radius: 8px;
      display: inline-block;
      text-transform: capitalize;
    }

    .status.paid {
      background-color: #dcfce7;
      color: #15803d;
    }

    .status.pending {
      background-color: #fef3c7;
      color: #d97706;
    }

    .status.failed {
      background-color: #fee2e2;
      color: #dc2626;
    }

    footer {
      background: #f9fafb;
      text-align: center;
      padding: 18px 20px;
      font-size: 0.9rem;
      color: #6b7280;
      border-top: 1px solid #e5e7eb;
    }

    .footer-link {
      color: #3c2f27;
      text-decoration: none;
      font-weight: 500;
    }

    .footer-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 600px) {
      .invoice-body {
        padding: 30px 20px;
      }

      .invoice-header {
        padding: 24px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="invoice-card">
    <div class="invoice-header">
      <h1>Invoice #{{ $transaction['invoice'] }}</h1>
      <span>Date: {{ \Carbon\Carbon::parse($transaction['created_at'])->format('F d, Y') }}</span>
    </div>

    <div class="invoice-body">
      <div class="section">
        <label>Name</label>
        <p>{{ $transaction['name'] }}</p>
      </div>
      <div class="section">
        <label>Email</label>
        <p>{{ $transaction['email'] }}</p>
      </div>
      <div class="section">
        <label>Remark</label>
        <p>{{ $transaction['remark'] ?? 'N/A' }}</p>
      </div>
      <div class="section">
        <label>Donation Type</label>
        <p>{{ ucfirst($transaction['donation_type']) }}</p>
      </div>
      <div class="section">
        <label>Payment Type</label>
        <p>{{ ucfirst($transaction['payment_type']) }}</p>
      </div>
      <div class="section">
        <label>Frequency</label>
        <p>{{ ucfirst($transaction['frequency'] ?? 'One-time') }}</p>
      </div>

      <div class="highlight-box">
        <label>Amount</label>
        <p class="amount">£{{ number_format($transaction['amount'], 2) }}</p>
        <label>Status</label>
        <span class="status {{ strtolower($transaction['payment_status']) }}">
          {{ ucfirst($transaction['payment_status']) }}
        </span>
      </div>
    </div>

    <footer>
      Need help? <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="footer-link">Contact support</a>
    </footer>
  </div>
</body>
</html>
