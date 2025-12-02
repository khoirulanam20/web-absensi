<!DOCTYPE html>
<html>
<head>
  <title>Slip Gaji - {{ $payroll->user->name }}</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .header { text-align: center; margin-bottom: 30px; }
    .info { margin-bottom: 20px; }
    .section { margin-bottom: 30px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
    .total { font-weight: bold; font-size: 18px; }
    .text-right { text-align: right; }
  </style>
</head>
<body>
  <div class="header">
    <h1>SLIP GAJI</h1>
    <p>Periode: {{ \Carbon\Carbon::parse($payroll->period . '-01')->format('F Y') }}</p>
  </div>

  <div class="info">
    <p><strong>Nama:</strong> {{ $payroll->user->name }}</p>
    <p><strong>NIP:</strong> {{ $payroll->user->nip ?? '-' }}</p>
    <p><strong>Divisi:</strong> {{ $payroll->user->division?->name ?? '-' }}</p>
    <p><strong>Jabatan:</strong> {{ $payroll->user->jobTitle?->name ?? '-' }}</p>
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

  <div class="section total">
    <p>Gaji Bersih (Take Home Pay): <span class="text-right">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span></p>
  </div>
</body>
</html>

