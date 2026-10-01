<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { color: #0066FF; text-align: center; margin-bottom: 5px; }
        h2 { color: #00E5CC; text-align: center; font-size: 14px; margin-top: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0066FF; padding-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .info { margin-bottom: 20px; }
        .info p { margin: 5px 0; }
        .total { font-size: 16px; font-weight: bold; text-align: right; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .status { padding: 5px 10px; border-radius: 4px; font-weight: bold; display: inline-block; }
        .status-success { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-failed { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bukti Pembayaran</h1>
        <h2>Invoice {{ $payment->invoice->invoice_number }}</h2>
    </div>

    <div class="info">
        <p><strong>Invoice:</strong> {{ $payment->invoice->invoice_number }}</p>
        <p><strong>Pelanggan:</strong> {{ $payment->invoice->customer->name }}</p>
        <p><strong>Paket:</strong> {{ $payment->invoice->customer->package->name ?? '-' }}</p>
        <p><strong>Periode:</strong> {{ $payment->invoice->period }}</p>
        <p><strong>Tanggal Pembayaran:</strong> {{ $payment->created_at->format('d M Y H:i') }}</p>
        <p><strong>Metode:</strong> {{ $payment->method->label() }}</p>
        <p><strong>Status:</strong> <span class="status status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pembayaran Invoice {{ $payment->invoice->invoice_number }}</td>
                <td style="text-align: right;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total: Rp {{ number_format($payment->amount, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        <p>SATAK - Konek Terus</p>
    </div>
</body>
</html>
