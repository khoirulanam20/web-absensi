<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Slip Gaji - {{ $payroll->user->name }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
      font-family: Arial, sans-serif; 
      padding: 30px;
      font-size: 12px;
      line-height: 1.6;
    }
    .header { 
      text-align: center; 
      margin-bottom: 30px;
      border-bottom: 2px solid #333;
      padding-bottom: 15px;
    }
    .header h1 {
      font-size: 24px;
      margin-bottom: 10px;
    }
    .info { 
      margin-bottom: 25px;
      padding: 15px;
      background-color: #f5f5f5;
      border-radius: 5px;
    }
    .info p {
      margin: 5px 0;
    }
    .section { 
      margin-bottom: 25px; 
    }
    .section h3 {
      margin-bottom: 10px;
      font-size: 14px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 5px;
    }
    table { 
      width: 100%; 
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td { 
      padding: 10px; 
      text-align: left; 
      border-bottom: 1px solid #ddd; 
    }
    th {
      background-color: #f0f0f0;
      font-weight: bold;
    }
    .total { 
      font-weight: bold; 
      font-size: 14px;
      background-color: #f9f9f9;
    }
    .text-right { 
      text-align: right; 
    }
    .net-salary {
      margin-top: 20px;
      padding: 15px;
      background-color: #e8f5e9;
      border: 2px solid #4caf50;
      border-radius: 5px;
      text-align: center;
    }
    .net-salary p {
      font-size: 18px;
      font-weight: bold;
      color: #2e7d32;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>SLIP GAJI</h1>
    <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($payroll->period . '-01')->format('F Y') }}</p>
    <p><strong>Tanggal Pembayaran:</strong> {{ \Carbon\Carbon::parse($payroll->payment_date)->format('d F Y') }}</p>
  </div>

  <div class="info">
    <p><strong>Nama:</strong> {{ $payroll->user->name }}</p>
    <p><strong>NIP:</strong> {{ $payroll->user->nip ?? '-' }}</p>
    <p><strong>Divisi:</strong> {{ $payroll->user->division?->name ?? '-' }}</p>
    <p><strong>Jabatan:</strong> {{ $payroll->user->jobTitle?->name ?? '-' }}</p>
    <p><strong>Kehadiran:</strong> {{ $payroll->total_attendance }} hari</p>
  </div>

  <div class="section">
    <h3>Penerimaan</h3>
    <table>
      <tr>
        <th>Komponen</th>
        <th class="text-right">Jumlah</th>
      </tr>
      <tr>
        <td>Gaji Pokok</td>
        <td class="text-right">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
      </tr>
      @foreach ($payroll->details->where('type', 'earning') as $detail)
        <tr>
          <td>{{ $detail->component_name }}</td>
          <td class="text-right">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
        </tr>
      @endforeach
      <tr class="total">
        <td>Total Penerimaan</td>
        <td class="text-right">Rp {{ number_format($payroll->basic_salary + $payroll->total_allowance, 0, ',', '.') }}</td>
      </tr>
    </table>
  </div>

  <div class="section">
    <h3>Potongan</h3>
    <table>
      <tr>
        <th>Komponen</th>
        <th class="text-right">Jumlah</th>
      </tr>
      @foreach ($payroll->details->where('type', 'deduction') as $detail)
        <tr>
          <td>{{ $detail->component_name }}</td>
          <td class="text-right">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
        </tr>
      @endforeach
      <tr class="total">
        <td>Total Potongan</td>
        <td class="text-right">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
      </tr>
    </table>
  </div>

  <div class="net-salary">
    <p>Gaji Bersih (Take Home Pay)</p>
    <p style="font-size: 24px; margin-top: 10px;">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</p>
  </div>
</body>
</html>

