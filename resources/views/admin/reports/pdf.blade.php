<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan - SATAK</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { color: #0066FF; text-align: center; margin-bottom: 5px; }
        h2 { color: #00E5CC; text-align: center; font-size: 14px; margin-top: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0066FF; padding-bottom: 15px; }
        .stats { display: flex; justify-content: space-around; margin-bottom: 30px; }
        .stat-box { text-align: center; padding: 15px; border: 1px solid #ddd; border-radius: 8px; width: 30%; }
        .stat-box h3 { margin: 0; font-size: 12px; color: #666; }
        .stat-box p { margin: 5px 0 0; font-size: 18px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .section { margin-bottom: 30px; }
        .section h3 { color: #0066FF; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan SATAK</h1>
        <h2>Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</h2>
    </div>

    <div class="stats">
        <div class="stat-box">
            <h3>Total Pendapatan</h3>
            <p style="color: #16a34a;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="stat-box">
            <h3>Jumlah Transaksi</h3>
            <p style="color: #2563eb;">{{ $paymentCount }}</p>
        </div>
        <div class="stat-box">
            <h3>Total Tunggakan</h3>
            <p style="color: #dc2626;">Rp {{ number_format($outstanding, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="section">
        <h3>Pendapatan per Metode</h3>
        <table>
            <thead>
                <tr>
                    <th>Metode</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueByMethod as $method => $total)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
                        <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Daftar Tunggakan</h3>
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Jatuh Tempo</th>
                    <th class="text-right">Sisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($overdueInvoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->due_date->format('d M Y') }}</td>
                        <td class="text-right">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        <p>SATAK - Konek Terus</p>
    </div>
</body>
</html>
