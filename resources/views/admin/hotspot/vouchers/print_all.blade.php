<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Semua Voucher</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f3f4f6; }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-header h1 { font-size: 18px; color: #333; }
        .print-header p { font-size: 12px; color: #666; margin-top: 4px; }

        .btn-print {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .btn-print:hover { background: #1d4ed8; }
        .btn-back {
            display: inline-block;
            background: #6b7280;
            color: #fff;
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 20px;
            margin-left: 8px;
            text-decoration: none;
        }

        .voucher-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .voucher-card {
            border: 2px dashed #374151;
            border-radius: 8px;
            padding: 16px 12px;
            text-align: center;
            background: #fff;
            page-break-inside: avoid;
        }
        .voucher-card .brand {
            font-size: 13px;
            font-weight: bold;
            color: #1f2937;
            letter-spacing: 1px;
        }
        .voucher-card .profile {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }
        .voucher-card .code {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 12px 0;
            color: #111827;
            font-family: 'Courier New', Courier, monospace;
        }
        .voucher-card .validity {
            font-size: 10px;
            color: #6b7280;
        }
        .voucher-card .footer {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
            font-size: 14px;
        }

        @media print {
            body { padding: 0; background: #fff; }
            .no-print { display: none !important; }
            .voucher-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }
            .voucher-card {
                border: 1.5px dashed #000;
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="print-header">
        <h1>Voucher Hotspot — SaSbilling</h1>
        <p>Total: {{ $vouchers->count() }} voucher</p>
    </div>

    <div class="no-print" style="text-align: center;">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Sekarang</button>
        <a href="{{ route('admin.hotspot.vouchers.index') }}" class="btn-back">← Kembali</a>
    </div>

    @if($vouchers->isEmpty())
        <div class="empty-state">Tidak ada voucher untuk dicetak.</div>
    @else
        <div class="voucher-grid">
            @foreach($vouchers as $voucher)
                <div class="voucher-card">
                    <div class="brand">SATAK HOTSPOT</div>
                    <div class="profile">{{ $voucher->profile->name ?? 'Voucher' }}</div>
                    <div class="code">{{ $voucher->code }}</div>
                    <div class="validity">Berlaku: {{ $voucher->profile->validity ?? '-' }}</div>
                    <div class="footer">Terima kasih telah menggunakan layanan kami</div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
