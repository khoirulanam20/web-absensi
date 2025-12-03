<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Invoice - {{ $invoice->order_number }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
      font-family: Arial, sans-serif; 
      padding: 40px;
      font-size: 12px;
      line-height: 1.6;
      color: #000;
    }
    .header { 
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #000;
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .logo {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .logo img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    .logo-fallback {
      width: 40px;
      height: 40px;
      background-color: #000;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: bold;
      font-size: 20px;
    }
    .invoice-title {
      font-size: 36px;
      font-weight: bold;
      letter-spacing: 1px;
      color: #000;
    }
    .order-number {
      text-align: right;
    }
    .order-number-label {
      font-size: 9px;
      color: #666;
      margin-bottom: 3px;
      text-transform: uppercase;
    }
    .order-number-value {
      font-size: 14px;
      font-weight: bold;
      color: #000;
    }
    .info-section {
      width: 100%;
      margin-bottom: 30px;
    }
    .info-row {
      width: 100%;
      display: table;
      table-layout: fixed;
    }
    .from-section {
      display: table-cell;
      width: 48%;
      vertical-align: top;
      padding-right: 2%;
    }
    .to-section {
      display: table-cell;
      width: 48%;
      text-align: right;
      vertical-align: top;
      padding-left: 2%;
    }
    .section-title {
      font-weight: bold;
      font-size: 10px;
      margin-bottom: 8px;
      color: #000;
      text-transform: uppercase;
    }
    .company-name {
      font-weight: bold;
      font-size: 13px;
      margin-bottom: 4px;
      color: #000;
    }
    .info-text {
      font-size: 10px;
      color: #555;
      line-height: 1.6;
      margin-bottom: 2px;
    }
    .invoice-date {
      margin-top: 12px;
      font-size: 10px;
      color: #555;
    }
    .invoice-date strong {
      color: #000;
    }
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }
    .items-table thead {
      background-color: #000;
      color: #fff;
    }
    .items-table th {
      padding: 10px;
      text-align: left;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
    }
    .items-table th.text-right {
      text-align: right;
    }
    .items-table td {
      padding: 10px;
      border-bottom: 1px solid #eee;
      font-size: 10px;
      color: #000;
    }
    .items-table tbody tr:last-child td {
      border-bottom: none;
    }
    .items-table tbody tr td:first-child {
      color: #000;
    }
    .items-table tbody tr td:not(:first-child) {
      color: #555;
    }
    .text-right {
      text-align: right;
    }
    .summary-section {
      width: 100%;
      margin-top: 30px;
    }
    .summary-row {
      width: 100%;
      display: table;
      table-layout: fixed;
    }
    .payment-info {
      display: table-cell;
      width: 48%;
      vertical-align: top;
      padding-right: 2%;
    }
    .totals {
      display: table-cell;
      width: 48%;
      vertical-align: top;
      padding-left: 2%;
    }
    .summary-item-row {
      display: table;
      width: 100%;
      margin-bottom: 6px;
      font-size: 10px;
    }
    .summary-item-row span {
      display: table-cell;
    }
    .summary-item-row span:first-child {
      text-align: left;
    }
    .summary-item-row span:last-child {
      text-align: right;
    }
    .summary-item-row span:first-child {
      color: #555;
    }
    .summary-item-row span:last-child {
      color: #000;
      font-weight: bold;
    }
    .total-box {
      background-color: #f5f5f5;
      padding: 12px 15px;
      margin-top: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .total-label {
      font-weight: bold;
      font-size: 12px;
      color: #000;
    }
    .total-amount {
      font-weight: bold;
      font-size: 16px;
      color: #000;
    }
    .payment-detail {
      font-size: 10px;
      margin-top: 20px;
      line-height: 1.8;
      color: #555;
    }
    .payment-detail strong {
      color: #000;
      text-transform: uppercase;
      font-size: 10px;
    }
    .payment-status {
      margin-top: 8px;
      font-size: 10px;
      font-weight: bold;
      color: #555;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <div class="header">
    <div class="header-left">
      <div class="logo">
        @if(isset($logoBase64) && $logoBase64)
          <img src="{{ $logoBase64 }}" alt="Logo">
        @else
          <div class="logo-fallback">F</div>
        @endif
      </div>
      <div class="invoice-title">INVOICE</div>
    </div>
    <div class="order-number">
      <div class="order-number-label">ORDER NUMBER:</div>
      <div class="order-number-value">{{ $invoice->order_number }}</div>
    </div>
  </div>

  <!-- FROM and INVOICE TO -->
  <div class="info-section">
    <div class="info-row">
      <div class="from-section">
        <div class="section-title">FROM:</div>
        <div class="company-name">{{ $invoice->sender_name }}</div>
        @if($invoice->sender_email)
          <div class="info-text">{{ $invoice->sender_email }}</div>
        @endif
        @if($invoice->sender_address)
          <div class="info-text">{{ $invoice->sender_address }}</div>
        @endif
      </div>
      <div class="to-section">
        <div class="section-title">INVOICE TO:</div>
        <div class="company-name">{{ $invoice->recipient_name }}</div>
        @if($invoice->recipient_email)
          <div class="info-text">{{ $invoice->recipient_email }}</div>
        @endif
        @if($invoice->recipient_address)
          <div class="info-text">{{ $invoice->recipient_address }}</div>
        @endif
        <div class="invoice-date">
          <strong>INVOICE DATE:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}
        </div>
      </div>
    </div>
  </div>

  <!-- Items Table -->
  <table class="items-table">
    <thead>
      <tr>
        <th>DESCRIPTION</th>
        <th>UNIT PRICE</th>
        <th>QTY</th>
        <th class="text-right">TOTAL</th>
      </tr>
    </thead>
    <tbody>
      @foreach($invoice->items as $item)
        <tr>
          <td>{{ $item->description }}</td>
          <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
          <td>{{ $item->quantity }}</td>
          <td class="text-right">Rp {{ number_format((float) $item->total, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Summary and Payment -->
  <div class="summary-section">
    <div class="summary-row">
      <div class="payment-info">
        <div class="payment-detail">
          <strong>SEND PAYMENT TO:</strong><br>
          @if($invoice->bank_name)
            {{ $invoice->bank_name }}<br>
          @endif
          @if($invoice->account_name)
            Account Name: {{ $invoice->account_name }}<br>
          @endif
          @if($invoice->account_number)
            Account No: {{ $invoice->account_number }}<br>
          @endif
          <span class="payment-status">Pay by: {{ ucfirst($invoice->payment_status) }}</span>
        </div>
      </div>
      <div class="totals">
        <div class="summary-item-row">
          <span>SUBTOTAL:</span>
          <span>Rp {{ number_format((float) $invoice->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item-row">
          <span>Tax:</span>
          <span>{{ (float) $invoice->tax > 0 ? 'Rp ' . number_format((float) $invoice->tax, 0, ',', '.') : '-' }}</span>
        </div>
        <div class="total-box">
          <span class="total-label">TOTAL:</span>
          <span class="total-amount">Rp {{ number_format((float) $invoice->total, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
