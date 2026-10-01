<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
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
        .status-paid { background: #dcfce7; color: #166534; }
        .status-unpaid { background: #fef3c7; color: #92400e; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice</h1>
        <h2>{{ $invoice->invoice_number }}</h2>
    </div>

    <div class="info">
        <p><strong>Periode:</strong> {{ $invoice->period }}</p>
        <p><strong>Pelanggan:</strong> {{ $invoice->customer->name }}</p>
        <p><strong>Paket:</strong> {{ $invoice->customer->package->name ?? '-' }}</p>
        <p><strong>Jatuh Tempo:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
        <p><strong>Status:</strong> <span class="status status-{{ $invoice->status->value }}">{{ ucfirst($invoice->status->value) }}</span></p>
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
                <td>{{ $invoice->customer->package->name ?? 'Paket Internet' }} - {{ $invoice->period }}</td>
                <td style="text-align: right;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
            </tr>
            @if($invoice->late_fee > 0)
            <tr>
                <td>Denda Keterlambatan</td>
                <td style="text-align: right;">Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($invoice->discount > 0)
            <tr>
                <td>Diskon</td>
                <td style="text-align: right;">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="total">
        Total: Rp {{ number_format($invoice->amount + $invoice->late_fee - $invoice->discount, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        <p>SATAK - Konek Terus</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
