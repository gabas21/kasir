<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; line-height: 1.4; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #6366F1; padding-bottom: 15px; }
        .header h1 { font-size: 20px; color: #6366F1; margin-bottom: 3px; }
        .header p { color: #666; font-size: 10px; }

        .meta { display: flex; margin-bottom: 15px; }
        .meta-item { margin-right: 30px; }
        .meta-label { color: #888; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        .meta-value { font-weight: bold; font-size: 12px; }

        .stats { width: 100%; margin-bottom: 20px; }
        .stats td { padding: 10px 15px; text-align: center; border: 1px solid #e5e7eb; }
        .stats .stat-label { color: #888; font-size: 9px; text-transform: uppercase; display: block; }
        .stats .stat-value { font-size: 16px; font-weight: bold; color: #6366F1; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data th { background: #6366F1; color: white; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px; }
        table.data td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        .total-row td { background: #f1f5f9; font-weight: bold; border-top: 2px solid #6366F1; }

        .top-products { margin-top: 20px; }
        .top-products h3 { font-size: 13px; color: #6366F1; margin-bottom: 8px; }

        .footer { margin-top: 25px; text-align: center; color: #999; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-cash { background: #d1fae5; color: #065f46; }
        .badge-qris { background: #dbeafe; color: #1e40af; }
        .badge-transfer { background: #ffedd5; color: #9a3412; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>☕ POS Cafe — Laporan Penjualan</h1>
        <p>Periode: {{ $startDate }} — {{ $endDate }}</p>
    </div>

    {{-- Meta --}}
    <table style="width:100%; margin-bottom: 15px;">
        <tr>
            <td style="width:50%">
                <span class="meta-label">Digenerate pada</span><br>
                <span class="meta-value">{{ $generatedAt }}</span>
            </td>
            <td style="width:50%; text-align:right;">
                <span class="meta-label">Filter</span><br>
                <span class="meta-value">{{ ucfirst($dateRange) }}</span>
            </td>
        </tr>
    </table>

    {{-- Summary Stats --}}
    <table class="stats" style="width:100%">
        <tr>
            <td>
                <span class="stat-label">Total Pendapatan</span>
                <span class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="stat-label">Total Transaksi</span>
                <span class="stat-value">{{ number_format($totalTransactions) }}</span>
            </td>
            <td>
                <span class="stat-label">Rata-rata / Trx</span>
                <span class="stat-value">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    {{-- Transaction Table --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:20%">Invoice</th>
                <th style="width:12%">Tanggal</th>
                <th style="width:12%">Kasir</th>
                <th style="width:10%">Metode</th>
                <th style="width:26%">Item</th>
                <th style="width:15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $trx)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td style="font-size:9px;">{{ $trx->invoice_number }}</td>
                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->user->name ?? 'System' }}</td>
                <td>
                    <span class="badge badge-{{ $trx->payment_method }}">
                        {{ strtoupper($trx->payment_method) }}
                    </span>
                </td>
                <td style="font-size:9px;">
                    @foreach($trx->details as $d)
                        {{ $d->product_name }} x{{ $d->qty }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td class="text-right font-bold">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="text-right">TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Top Products --}}
    @if($topProducts->count() > 0)
    <div class="top-products">
        <h3>🔥 Produk Terlaris</h3>
        <table class="data">
            <thead>
                <tr>
                    <th style="width:5%">#</th>
                    <th>Produk</th>
                    <th class="text-center" style="width:15%">Qty</th>
                    <th class="text-right" style="width:25%">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $p->product_name }}</td>
                    <td class="text-center">{{ $p->total_qty }} pcs</td>
                    <td class="text-right font-bold">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>POS Cafe — Digenerate otomatis oleh sistem · {{ $generatedAt }}</p>
    </div>
</body>
</html>
