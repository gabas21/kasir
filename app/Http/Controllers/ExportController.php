<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    private function getDateRange(Request $request): array
    {
        $range = $request->get('range', 'today');
        $start = match ($range) {
            'today'  => now()->startOfDay(),
            '7days'  => now()->subDays(6)->startOfDay(),
            '30days' => now()->subDays(29)->startOfDay(),
            'custom' => Carbon::parse($request->get('from', now()->toDateString()))->startOfDay(),
            default  => now()->startOfDay(),
        };
        $end = $range === 'custom'
            ? Carbon::parse($request->get('to', now()->toDateString()))->endOfDay()
            : now()->endOfDay();

        return [$start, $end, $range];
    }

    private function getTransactions($start, $end)
    {
        return Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->with(['user', 'details'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request)
    {
        [$start, $end, $range] = $this->getDateRange($request);
        $transactions = $this->getTransactions($start, $end);

        $data = [
            'transactions'    => $transactions,
            'totalRevenue'    => $transactions->sum('total_price'),
            'totalTransactions' => $transactions->count(),
            'averageTransaction' => $transactions->count() > 0 ? (int)($transactions->sum('total_price') / $transactions->count()) : 0,
            'dateRange'       => $range,
            'startDate'       => $start->format('d M Y'),
            'endDate'         => $end->format('d M Y'),
            'generatedAt'     => now()->format('d M Y H:i'),
            'topProducts'     => TransactionDetail::whereHas('transaction', fn($q) =>
                $q->where('status', 'paid')->whereBetween('created_at', [$start, $end])
            )
                ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
                ->groupBy('product_name')
                ->orderByDesc('total_qty')
                ->limit(10)
                ->get(),
        ];

        $pdf = Pdf::loadView('exports.sales-report-pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Laporan-Penjualan-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export laporan ke Excel (.xls) — format HTML Table yang dibaca Excel secara native
     * Mendukung: warna header, border, auto-fit, number format, summary
     */
    public function exportCsv(Request $request)
    {
        [$start, $end, $range] = $this->getDateRange($request);
        $transactions = $this->getTransactions($start, $end);

        $filename = 'Laporan-Penjualan-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.xls';

        $totalRevenue = $transactions->sum('total_price');
        $totalPaid    = $transactions->sum('paid_amount');
        $totalChange  = $transactions->sum('change_amount');
        $totalDiscount = $transactions->sum('discount_amount');
        $trxCount     = $transactions->count();
        $avgTransaction = $trxCount > 0 ? (int)($totalRevenue / $trxCount) : 0;

        $rangeLabel = match ($range) {
            'today'  => 'Hari Ini (' . $start->format('d M Y') . ')',
            '7days'  => '7 Hari Terakhir',
            '30days' => '30 Hari Terakhir',
            'custom' => $start->format('d M Y') . ' — ' . $end->format('d M Y'),
            default  => $start->format('d M Y'),
        };

        // Top products
        $topProducts = TransactionDetail::whereHas('transaction', fn($q) =>
            $q->where('status', 'paid')->whereBetween('created_at', [$start, $end])
        )
            ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Build HTML table for Excel
        $html = '
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <!--[if gte mso 9]>
            <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>Laporan Penjualan</x:Name>
                            <x:WorksheetOptions>
                                <x:DisplayGridlines/>
                            </x:WorksheetOptions>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
            </xml>
            <![endif]-->
            <style>
                td, th { 
                    mso-number-format:\@; 
                    font-family: Calibri, Arial, sans-serif;
                    font-size: 11pt;
                    padding: 6px 10px;
                }
                .number { mso-number-format: "#,##0"; text-align: right; }
                .currency { mso-number-format: "Rp #,##0"; text-align: right; }
            </style>
        </head>
        <body>';

        // ===== HEADER SECTION =====
        $html .= '
        <table>
            <tr>
                <td colspan="10" style="font-size:18pt; font-weight:bold; color:#755833; padding-bottom:4px;">☕ ELITE CAFE</td>
            </tr>
            <tr>
                <td colspan="10" style="font-size:14pt; font-weight:bold; color:#333;">LAPORAN PENJUALAN</td>
            </tr>
            <tr>
                <td colspan="10" style="font-size:10pt; color:#666;">Periode: ' . $rangeLabel . '</td>
            </tr>
            <tr>
                <td colspan="10" style="font-size:9pt; color:#999;">Dicetak: ' . now()->format('d M Y H:i') . ' WIB</td>
            </tr>
            <tr><td colspan="10"></td></tr>
        </table>';

        // ===== SUMMARY CARDS =====
        $html .= '
        <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; border-color:#ddd;">
            <tr style="background-color:#755833; color:#fff; font-weight:bold;">
                <td>Total Pendapatan</td>
                <td>Jumlah Transaksi</td>
                <td>Rata-rata / Transaksi</td>
                <td>Total Diskon</td>
            </tr>
            <tr style="font-size:12pt; font-weight:bold; text-align:center;">
                <td class="currency">' . number_format($totalRevenue, 0, ',', '.') . '</td>
                <td class="number">' . $trxCount . '</td>
                <td class="currency">' . number_format($avgTransaction, 0, ',', '.') . '</td>
                <td class="currency">' . number_format($totalDiscount, 0, ',', '.') . '</td>
            </tr>
        </table>';

        $html .= '<table><tr><td></td></tr></table>';

        // ===== TRANSACTION TABLE =====
        $html .= '
        <table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border-color:#ddd;">
            <tr style="background-color:#755833; color:#ffffff; font-weight:bold; font-size:10pt;">
                <th style="text-align:center; width:30px;">No</th>
                <th style="width:220px;">No. Invoice</th>
                <th style="width:130px;">Tanggal & Waktu</th>
                <th style="width:120px;">Kasir</th>
                <th style="width:80px; text-align:center;">Bayar</th>
                <th style="width:250px;">Item Pesanan</th>
                <th style="width:50px; text-align:center;">Qty</th>
                <th style="width:100px; text-align:right;">Total</th>
                <th style="width:100px; text-align:right;">Dibayar</th>
                <th style="width:100px; text-align:right;">Kembalian</th>
            </tr>';

        foreach ($transactions as $i => $trx) {
            $items = $trx->details->map(fn($d) => $d->product_name . ' ×' . $d->qty)->implode(', ');
            $totalQty = $trx->details->sum('qty');
            $bgColor = $i % 2 === 0 ? '#ffffff' : '#f9f6f1';

            $html .= '
            <tr style="background-color:' . $bgColor . '; font-size:10pt;">
                <td style="text-align:center;">' . ($i + 1) . '</td>
                <td style="font-family:Consolas,monospace; font-size:9pt;">' . $trx->invoice_number . '</td>
                <td>' . $trx->created_at->format('d/m/Y H:i') . '</td>
                <td>' . ($trx->user->name ?? 'System') . '</td>
                <td style="text-align:center; text-transform:uppercase;">' . strtoupper($trx->payment_method) . '</td>
                <td style="font-size:9pt;">' . $items . '</td>
                <td class="number">' . $totalQty . '</td>
                <td class="currency">' . number_format($trx->total_price, 0, ',', '.') . '</td>
                <td class="currency">' . number_format($trx->paid_amount, 0, ',', '.') . '</td>
                <td class="currency">' . number_format($trx->change_amount, 0, ',', '.') . '</td>
            </tr>';
        }

        // TOTAL ROW
        $html .= '
            <tr style="background-color:#755833; color:#ffffff; font-weight:bold; font-size:11pt;">
                <td colspan="6" style="text-align:right; padding-right:15px;">GRAND TOTAL</td>
                <td class="number" style="color:#fff;">' . $transactions->sum(fn($t) => $t->details->sum('qty')) . '</td>
                <td class="currency" style="color:#fff;">' . number_format($totalRevenue, 0, ',', '.') . '</td>
                <td class="currency" style="color:#fff;">' . number_format($totalPaid, 0, ',', '.') . '</td>
                <td class="currency" style="color:#fff;">' . number_format($totalChange, 0, ',', '.') . '</td>
            </tr>
        </table>';

        $html .= '<table><tr><td></td></tr></table>';

        // ===== TOP PRODUCTS TABLE =====
        if ($topProducts->count() > 0) {
            $html .= '
            <table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border-color:#ddd;">
                <tr>
                    <td colspan="4" style="font-size:12pt; font-weight:bold; color:#755833; border:none; padding-bottom:8px;">🏆 Top 10 Produk Terlaris</td>
                </tr>
                <tr style="background-color:#e8ddd0; font-weight:bold; font-size:10pt; color:#333;">
                    <th style="text-align:center; width:30px;">No</th>
                    <th style="width:250px;">Nama Produk</th>
                    <th style="width:80px; text-align:right;">Qty Terjual</th>
                    <th style="width:120px; text-align:right;">Total Pendapatan</th>
                </tr>';

            foreach ($topProducts as $j => $product) {
                $bgColor = $j % 2 === 0 ? '#ffffff' : '#f9f6f1';
                $medal = match ($j) { 0 => '🥇 ', 1 => '🥈 ', 2 => '🥉 ', default => '' };

                $html .= '
                <tr style="background-color:' . $bgColor . '; font-size:10pt;">
                    <td style="text-align:center;">' . ($j + 1) . '</td>
                    <td>' . $medal . $product->product_name . '</td>
                    <td class="number">' . number_format($product->total_qty, 0, ',', '.') . '</td>
                    <td class="currency">' . number_format($product->total_revenue, 0, ',', '.') . '</td>
                </tr>';
            }

            $html .= '</table>';
        }

        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}
