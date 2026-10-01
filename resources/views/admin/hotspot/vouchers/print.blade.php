<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Voucher - {{ $voucher->code }}</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .voucher { border: 2px dashed #333; padding: 30px; text-align: center; width: 300px; }
        .voucher h2 { margin: 0 0 10px; font-size: 18px; }
        .voucher .code { font-size: 24px; font-weight: bold; letter-spacing: 2px; margin: 20px 0; }
        .voucher .info { font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="voucher">
        <h2>SaSbilling Hotspot</h2>
        <p class="info">{{ $voucher->profile->name ?? 'Voucher' }}</p>
        <div class="code">{{ $voucher->code }}</div>
        <p class="info">Berlaku hingga: {{ $voucher->profile->validity ?? '-' }}</p>
        <p class="info">Terima kasih telah menggunakan layanan kami</p>
    </div>
</body>
</html>
