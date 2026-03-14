<?php

namespace App\Livewire;

use App\Services\DashboardService;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class OwnerDashboard extends Component
{
    public string $period = 'today'; // today, 7days, 30days

    protected function getService(): DashboardService
    {
        return app(DashboardService::class);
    }

    // ==========================================
    // STAT CARDS
    // ==========================================

    #[Computed]
    public function revenueStats(): array
    {
        $service = $this->getService();
        $startDate = $service->getStartDate($this->period);
        $prevStartDate = $startDate->copy()->subDays(match($this->period) { 'today' => 1, '7days' => 7, '30days' => 30, default => 1 });
        
        return $service->getRevenueStat($startDate, $prevStartDate, $this->period);
    }
    
    #[Computed]
    public function todayRevenue(): int { return $this->revenueStats['today']; }
    #[Computed]
    public function previousRevenue(): int { return $this->revenueStats['previous']; }
    #[Computed]
    public function revenueChange(): float { return $this->revenueStats['change']; }

    #[Computed]
    public function transactionStats(): array
    {
        $service = $this->getService();
        $startDate = $service->getStartDate($this->period);
        $prevStartDate = $startDate->copy()->subDays(match($this->period) { 'today' => 1, '7days' => 7, '30days' => 30, default => 1 });

        return $service->getTransactionStat($startDate, $prevStartDate, $this->period);
    }

    #[Computed]
    public function totalTransactions(): int { return $this->transactionStats['total']; }
    #[Computed]
    public function previousTransactions(): int { return $this->transactionStats['previous']; }
    #[Computed]
    public function transactionChange(): float { return $this->transactionStats['change']; }

    #[Computed]
    public function averageTransaction(): int
    {
        return $this->getService()->getAverageTransaction($this->todayRevenue, $this->totalTransactions);
    }

    #[Computed]
    public function totalItemsSold(): int
    {
        return $this->getService()->getTotalItemsSold($this->getService()->getStartDate($this->period));
    }

    // ==========================================
    // CHART DATA
    // ==========================================

    #[Computed]
    public function chartData(): array
    {
        $service = $this->getService();
        return $service->getChartData($this->period, $service->getStartDate($this->period));
    }

    // ==========================================
    // TOP & WORST PRODUCTS
    // ==========================================

    #[Computed]
    public function topProducts(): array
    {
        return $this->getService()->getTopProducts($this->getService()->getStartDate($this->period));
    }

    #[Computed]
    public function worstProducts(): array
    {
        return $this->getService()->getWorstProducts($this->getService()->getStartDate($this->period));
    }

    // ==========================================
    // PAYMENT METHOD BREAKDOWN
    // ==========================================

    #[Computed]
    public function paymentBreakdown(): array
    {
        return $this->getService()->getPaymentBreakdown(
            $this->getService()->getStartDate($this->period), 
            $this->totalTransactions
        );
    }

    // ==========================================
    // KASIR PERFORMANCE
    // ==========================================

    #[Computed]
    public function cashierPerformance(): array
    {
        return $this->getService()->getCashierPerformance($this->getService()->getStartDate($this->period));
    }

    // ==========================================
    // RECENT TRANSACTIONS
    // ==========================================

    #[Computed]
    public function recentTransactions()
    {
        return $this->getService()->getRecentTransactions(10);
    }

    public function render()
    {
        return view('livewire.owner-dashboard');
    }
}
