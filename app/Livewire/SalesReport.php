<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class SalesReport extends Component
{
    use WithPagination;

    public string $dateRange = 'today'; // today, 7days, 30days, custom
    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount(): void
    {
        $this->dateFrom = now()->toDateString();
        $this->dateTo = now()->toDateString();
    }

    /** Tanggal awal berdasarkan filter */
    private function getStartDate(): Carbon
    {
        return match ($this->dateRange) {
            'today'  => now()->startOfDay(),
            '7days'  => now()->subDays(6)->startOfDay(),
            '30days' => now()->subDays(29)->startOfDay(),
            'custom' => Carbon::parse($this->dateFrom)->startOfDay(),
            default  => now()->startOfDay(),
        };
    }

    /** Tanggal akhir berdasarkan filter */
    private function getEndDate(): Carbon
    {
        return match ($this->dateRange) {
            'custom' => Carbon::parse($this->dateTo)->endOfDay(),
            default  => now()->endOfDay(),
        };
    }

    /** Query dasar transaksi berdasarkan filter tanggal */
    private function baseQuery()
    {
        return Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$this->getStartDate(), $this->getEndDate()]);
    }

    /** Total transaksi */
    #[Computed]
    public function totalTransactions(): int
    {
        return $this->baseQuery()->count();
    }

    /** Total pendapatan */
    #[Computed]
    public function totalRevenue(): int
    {
        return (int) $this->baseQuery()->sum('total_price');
    }

    /** Rata-rata per transaksi */
    #[Computed]
    public function averageTransaction(): int
    {
        $count = $this->totalTransactions; // tanpa () — akses sebagai Computed property
        return $count > 0 ? (int) ($this->totalRevenue / $count) : 0;
    }

    /** Total item terjual */
    #[Computed]
    public function totalItemsSold(): int
    {
        return (int) TransactionDetail::whereHas('transaction', function ($q) {
            $q->where('status', 'paid')
              ->whereBetween('created_at', [$this->getStartDate(), $this->getEndDate()]);
        })->sum('qty');
    }

    /** Breakdown per metode pembayaran */
    #[Computed]
    public function paymentBreakdown(): array
    {
        $data = $this->baseQuery()
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_price) as total')
            ->groupBy('payment_method')
            ->get();

        $totalCount = $this->totalTransactions; // tanpa () — akses sebagai Computed property
        $methods = ['cash' => 'Cash', 'qris' => 'QRIS', 'transfer' => 'Transfer'];
        $result = [];

        foreach ($methods as $key => $label) {
            $row = $data->firstWhere('payment_method', $key);
            $result[] = [
                'method'     => $label,
                'key'        => $key,
                'count'      => $row ? $row->count : 0,
                'total'      => $row ? (int) $row->total : 0,
                'percentage' => $totalCount > 0 && $row ? round(($row->count / $totalCount) * 100, 1) : 0,
            ];
        }

        return $result;
    }

    /** Top 5 produk terlaris */
    #[Computed]
    public function topProducts(): array
    {
        return TransactionDetail::whereHas('transaction', function ($q) {
            $q->where('status', 'paid')
              ->whereBetween('created_at', [$this->getStartDate(), $this->getEndDate()]);
        })
            ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /** Reset pagination saat filter berubah */
    public function updatedDateRange(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $transactions = $this->baseQuery()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.sales-report', [
            'transactions' => $transactions,
        ]);
    }
}
