<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket Resmi Anda</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #4f46e5; margin: 0; padding: 30px; color: #ffffff; }
        .card { background: #ffffff; color: #333333; max-width: 450px; margin: 0 auto; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background: #eef2ff; padding: 20px; text-align: center; border-bottom: 2px dashed #c7d2fe; color: #4f46e5; }
        .content { padding: 30px; }
        .grid { display: table; width: 100%; }
        .row { display: table-row; }
        .col { display: table-cell; width: 50%; padding-bottom: 15px; }
        .label { font-size: 11px; color: #94a3b8; font-weight: bold; text-transform: uppercase; }
        .value { font-size: 14px; font-weight: bold; }
        .qr-box { background: #f8fafc; padding: 20px; text-align: center; border-radius: 15px; margin-top: 15px; }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Pembayaran Sukses!</h2>
        <p style="color: #e0e7ff; margin: 5px 0 0 0;">Tiket digital Anda telah terbit.</p>
    </div>
    <div class="card">
        <div class="header">
            <span style="font-size: 11px; font-weight: bold; letter-spacing: 1px;">E-TICKET RESMI</span>
            <h3 style="margin: 5px 0 0 0; font-size: 20px;">{{ $transaction->event->title }}</h3>
        </div>
        <div class="content">
            <div class="grid">
                <div class="row">
                    <div class="col">
                        <div class="label">Nama Pembeli</div>
                        <div class="value">{{ $transaction->customer_name }}</div>
                    </div>
                    <div class="col">
                        <div class="label">Tanggal & Waktu</div>
                        <div class="value">{{ \Carbon\Carbon::parse($transaction->event->date)->format('d M, H:i') }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="label">Order ID</div>
                        <div class="value">{{ $transaction->order_id }}</div>
                    </div>
                    <div class="col">
                        <div class="label">Lokasi</div>
                        <div class="value">{{ $transaction->event->location }}</div>
                    </div>
                </div>
            </div>
            <div class="qr-box">
                <div class="label" style="margin-bottom: 10px;">Scan QR untuk Check-in</div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($transaction->order_id) }}" alt="QR" width="150" height="150">
                <div style="font-family: monospace; font-weight: bold; margin-top: 10px; color: #1e293b;">{{ $transaction->order_id }}</div>
            </div>
        </div>
    </div>
</body>
</html>