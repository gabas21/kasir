<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class DashboardService
{
    /**
     * Dapatkan tanggal mulai berdasarkan string periode
     */
    public function getStartDate(string $period): CarbonInterface
    {
        return match ($period) {
            'today'  => now()->startOfDay(),
            '7days'  => now()->subDays(6)->startOfDay(),
            '30days' => now()->subDays(29)->startOfDay(),
            default  => now()->startOfDay(),
        };
    }

    /**
     * Query dasar untuk transaksi sukses pada rentang waktu saat ini
     */
    public function baseQuery(Carbon $startDate)
    {
        return Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, now()->endOfDay()]);
    }

    /**
     * Query dasar untuk transaksi sukses pada rentang waktu sebelumnya (untuk perbandingan tren)
     */
    public function previousBaseQuery(string $period, Carbon $startDate)
    {
        $days = match ($period) {
            'today'  => 1,
            '7days'  => 7,
            '30days' => 30,
            default  => 1,
        };

        $prevStart = $startDate->copy()->subDays($days);
        $prevEnd = $startDate->copy()->subSecond();

        return Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$prevStart, $prevEnd]);
    }

    public function getRevenueStat(Carbon $startDate, Carbon $prevStartDate, string $period): array
    {
        $baseQ = $this->baseQuery($startDate);
        $prevBaseQ = $this->previousBaseQuery($period, $startDate);

        $todayRevenue = (int) (clone $baseQ)->sum('total_price');
        $previousRevenue = (int) (clone $prevBaseQ)->sum('total_price');
        
        $revenueChange = 0;
        if ($previousRevenue === 0) {
            $revenueChange = $todayRevenue > 0 ? 100 : 0;
        } else {
            $revenueChange = round((($todayRevenue - $previousRevenue) / $previousRevenue) * 100, 1);
        }

        return [
            'today'    => $todayRevenue,
            'previous' => $previousRevenue,
            'change'   => $revenueChange
        ];
    }

    public function getTransactionStat(Carbon $startDate, Carbon $prevStartDate, string $period): array
    {
        $baseQ = $this->baseQuery($startDate);
        $prevBaseQ = $this->previousBaseQuery($period, $startDate);

        $totalCount = (clone $baseQ)->count();
        $prevCount = (clone $prevBaseQ)->count();
        
        $change = 0;
        if ($prevCount === 0) {
            $change = $totalCount > 0 ? 100 : 0;
        } else {
            $change = round((($totalCount - $prevCount) / $prevCount) * 100, 1);
        }

        return [
            'total'    => $totalCount,
            'previous' => $prevCount,
            'change'   => $change
        ];
    }

    public function getAverageTransaction(int $revenue, int $transactionsCount): int
    {
        return $transactionsCount > 0 ? (int) ($revenue / $transactionsCount) : 0;
    }

    public function getTotalItemsSold(Carbon $startDate): int
    {
        return (int) TransactionDetail::whereHas('transaction', function ($q) use ($startDate) {
            $q->where('status', 'paid')
              ->whereBetween('created_at', [$startDate, now()->endOfDay()]);
        })->sum('qty');
    }

    public function getChartData(string $period, Carbon $startDate): array
    {
        $baseQ = clone $this->baseQuery($startDate);

        if ($period === 'today') {
            $data = $baseQ
                ->selectRaw('HOUR(created_at) as label, SUM(total_price) as total')
                ->groupByRaw('HOUR(created_at)')
                ->orderByRaw('HOUR(created_at)')
                ->pluck('total', 'label')
                ->toArray();

            $labels = [];
            $values = [];
            for ($h = 0; $h < 24; $h++) {
                $labels[] = str_pad((string)$h, 2, '0', STR_PAD_LEFT) . ':00';
                $values[] = $data[$h] ?? 0;
            }

            return ['labels' => $labels, 'values' => $values];
        }

        $days = $period === '7days' ? 7 : 30;
        $data = $baseQ
            ->selectRaw('DATE(created_at) as label, SUM(total_price) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->pluck('total', 'label')
            ->toArray();

        $labels = [];
        $values = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $values[] = $data[$date] ?? 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function getTopProducts(Carbon $startDate, int $limit = 5): array
    {
        return TransactionDetail::whereHas('transaction', function ($q) use ($startDate) {
            $q->where('status', 'paid')
              ->whereBetween('created_at', [$startDate, now()->endOfDay()]);
        })
            ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getWorstProducts(Carbon $startDate, int $limit = 5): array
    {
        return TransactionDetail::whereHas('transaction', function ($q) use ($startDate) {
            $q->where('status', 'paid')
              ->whereBetween('created_at', [$startDate, now()->endOfDay()]);
        })
            ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderBy('total_qty')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getPaymentBreakdown(Carbon $startDate, int $totalTransactionsCount): array
    {
        $data = $this->baseQuery($startDate)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_price) as total')
            ->groupBy('payment_method')
            ->get();

        $methods = ['cash' => 'Cash', 'qris' => 'QRIS', 'transfer' => 'Transfer'];
        $result = [];

        foreach ($methods as $key => $label) {
            $row = $data->firstWhere('payment_method', $key);
            $result[] = [
                'method'     => $label,
                'key'        => $key,
                'count'      => $row ? $row->count : 0,
                'total'      => $row ? (int) $row->total : 0,
                'percentage' => $totalTransactionsCount > 0 && $row ? round(($row->count / $totalTransactionsCount) * 100, 1) : 0,
            ];
        }

        return $result;
    }

    public function getCashierPerformance(Carbon $startDate): array
    {
        return Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, now()->endOfDay()])
            ->with('user')
            ->selectRaw('user_id, COUNT(*) as trx_count, SUM(total_price) as total_revenue')
            ->groupBy('user_id')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($item) {
                return [
                    'name'      => $item->user->name ?? 'Unknown',
                    'trx_count' => $item->trx_count,
                    'revenue'   => (int) $item->total_revenue,
                    'avg'       => $item->trx_count > 0 ? (int) ($item->total_revenue / $item->trx_count) : 0,
                ];
            })
            ->toArray();
    }

    public function getRecentTransactions(int $limit = 10)
    {
        return Transaction::where('status', 'paid')
            ->with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
