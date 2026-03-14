<div class="py-6 text-text-main" x-data="ownerDashboard()" x-init="initChart()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="">
                <h1 class="text-3xl font-bold text-text-main">
                    Dashboard Owner
                </h1>
                <p class="text-text-muted mt-1">Pantau performa cafe secara realtime.</p>
            </div>

            {{-- Period Filter --}}
            <div class="bg-surface p-1 rounded-lg border border-border inline-flex gap-1">
                <button wire:click="$set('period', 'today')"
                        class="px-4 py-2 rounded-md text-sm font-semibold transition-all duration-200
                               {{ $period === 'today' ? 'bg-gold text-white shadow-sm' : 'text-text-muted hover:text-text-main hover:bg-cream' }}">
                    Hari Ini
                </button>
                <button wire:click="$set('period', '7days')"
                        class="px-4 py-2 rounded-md text-sm font-semibold transition-all duration-200
                               {{ $period === '7days' ? 'bg-gold text-white shadow-sm' : 'text-text-muted hover:text-text-main hover:bg-cream' }}">
                    7 Hari
                </button>
                <button wire:click="$set('period', '30days')"
                        class="px-4 py-2 rounded-md text-sm font-semibold transition-all duration-200
                               {{ $period === '30days' ? 'bg-gold text-white shadow-sm' : 'text-text-muted hover:text-text-main hover:bg-cream' }}">
                    30 Hari
                </button>
            </div>
        </div>

        {{-- Loading Overlay --}}
        <div wire:loading.delay.longer class="fixed inset-0 z-50 bg-cream/80 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-white p-5 rounded-lg border border-border text-center shadow-xl">
                <svg class="animate-spin h-8 w-8 text-gold mx-auto mb-3" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-text-muted font-medium">Memuat data...</span>
            </div>
        </div>

        {{-- ==========================================
             STAT CARDS (4 cols)
        ========================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            {{-- Pendapatan --}}
            <div class="bg-white border border-border rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-lg bg-gold/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if($this->revenueChange != 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                     {{ $this->revenueChange > 0 ? 'bg-active/10 text-active border border-active/20' : 'bg-danger/10 text-danger border border-danger/20' }}">
                            {{ $this->revenueChange > 0 ? '↑' : '↓' }} {{ abs($this->revenueChange) }}%
                        </span>
                    @endif
                </div>
                <p class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-1">Pendapatan</p>
                <h3 class="text-2xl font-bold text-gold">Rp {{ number_format($this->todayRevenue, 0, ',', '.') }}</h3>
            </div>

            {{-- Transaksi --}}
            <div class="bg-white border border-border rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-lg bg-gold/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    @if($this->transactionChange != 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                     {{ $this->transactionChange > 0 ? 'bg-active/10 text-active border border-active/20' : 'bg-danger/10 text-danger border border-danger/20' }}">
                            {{ $this->transactionChange > 0 ? '↑' : '↓' }} {{ abs($this->transactionChange) }}%
                        </span>
                    @endif
                </div>
                <p class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-1">Transaksi</p>
                <h3 class="text-2xl font-bold">{{ number_format($this->totalTransactions) }}</h3>
            </div>

            {{-- Rata-rata --}}
            <div class="bg-white border border-border rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-lg bg-gold/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-1">Rata-rata / Trx</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format($this->averageTransaction, 0, ',', '.') }}</h3>
            </div>

            {{-- Item Terjual --}}
            <div class="bg-white border border-border rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-lg bg-gold/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-1">Item Terjual</p>
                <h3 class="text-2xl font-bold">{{ number_format($this->totalItemsSold) }} <span class="text-lg text-text-muted font-normal">pcs</span></h3>
            </div>
        </div>

        {{-- ==========================================
             ROW 2 — Revenue Chart + Payment Breakdown
        ========================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            {{-- Chart — 2 cols --}}
            <div class="lg:col-span-2 bg-white border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex justify-between items-center">
                    <h3 class="font-bold text-lg text-text-main">Grafik Pendapatan</h3>
                    <span class="text-xs text-text-muted font-medium bg-surface px-3 py-1 rounded-full border border-border">
                        {{ $period === 'today' ? 'Per Jam' : 'Per Hari' }}
                    </span>
                </div>
                <div class="p-6">
                    <div id="revenueChart" class="w-full" style="height: 300px;" wire:ignore></div>
                </div>
            </div>

            {{-- Payment Breakdown — 1 col --}}
            <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="font-bold text-lg text-text-main">Metode Pembayaran</h3>
                </div>
                <div class="p-6 space-y-5">
                    @forelse($this->paymentBreakdown as $payment)
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="font-medium flex items-center gap-2 text-sm">
                                    @if($payment['key'] == 'cash')
                                        <span class="w-2.5 h-2.5 rounded-full bg-active"></span>
                                    @elseif($payment['key'] == 'qris')
                                        <span class="w-2.5 h-2.5 rounded-full bg-gold"></span>
                                    @else
                                        <span class="w-2.5 h-2.5 rounded-full bg-sidebar"></span>
                                    @endif
                                    {{ $payment['method'] }}
                                </span>
                                <span class="text-sm font-bold text-text-muted">{{ $payment['percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-surface rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full transition-all duration-700 ease-out bg-gold"
                                     style="width: {{ $payment['percentage'] }}%;"></div>
                            </div>
                            <div class="flex justify-between text-xs text-text-muted mt-1">
                                <span>{{ $payment['count'] }} trx</span>
                                <span>Rp {{ number_format($payment['total'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-text-muted text-sm">Belum ada data pembayaran.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==========================================
             ROW 3 — Top Products + Worst Products + Kasir Performance
        ========================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            {{-- Top 5 --}}
            <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center gap-2">
                    <span class="text-lg">🔥</span>
                    <h3 class="font-bold text-lg text-text-main">Menu Terlaris</h3>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($this->topProducts as $index => $product)
                        <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-white/5 transition-colors duration-150">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                        {{ $index === 0 ? 'bg-gold text-white shadow-md' : 'bg-surface border border-border text-text-muted' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm truncate">{{ $product['product_name'] }}</p>
                                <p class="text-xs text-gold font-semibold">{{ $product['total_qty'] }} pcs</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-bold text-sm">Rp {{ number_format($product['total_revenue'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <p class="text-text-muted text-sm">Belum ada produk terjual.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Worst 5 --}}
            <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center gap-2">
                    <span class="text-lg">📉</span>
                    <h3 class="font-bold text-lg text-text-main">Kurang Diminati</h3>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($this->worstProducts as $index => $product)
                        <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-white/5 transition-colors duration-150">
                            <div class="w-8 h-8 rounded-full bg-danger/10 border border-danger/20 flex items-center justify-center font-bold text-sm text-danger">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm truncate">{{ $product['product_name'] }}</p>
                                <p class="text-xs text-danger font-semibold">{{ $product['total_qty'] }} pcs</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-bold text-sm text-text-muted">Rp {{ number_format($product['total_revenue'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <p class="text-text-muted text-sm">Belum ada data.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Kasir Performance --}}
            <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center gap-2">
                    <span class="text-lg">👤</span>
                    <h3 class="font-bold text-lg text-text-main">Performa Kasir</h3>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($this->cashierPerformance as $index => $cashier)
                        <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-white/5 transition-colors duration-150">
                            <div class="w-9 h-9 rounded-full bg-gold/10 border border-gold/20
                                        flex items-center justify-center font-bold text-sm text-gold">
                                {{ substr($cashier['name'], 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm truncate">{{ $cashier['name'] }}</p>
                                <p class="text-xs text-text-muted">{{ $cashier['trx_count'] }} trx · avg Rp {{ number_format($cashier['avg'], 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-bold text-sm text-gold">Rp {{ number_format($cashier['revenue'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <p class="text-text-muted text-sm">Belum ada data kasir.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==========================================
             ROW 4 — Recent Transactions
        ========================================== --}}
            <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex justify-between items-center">
                    <h3 class="font-bold text-lg text-text-main">Transaksi Terbaru</h3>
                    <a href="{{ route('reports.index') }}" class="text-sm text-gold hover:opacity-80 font-medium transition-colors">
                        Lihat Semua →
                    </a>
                </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-text-muted uppercase bg-surface border-b border-border">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Invoice</th>
                            <th class="px-6 py-3 font-semibold">Kasir</th>
                            <th class="px-6 py-3 font-semibold">Metode</th>
                            <th class="px-6 py-3 font-semibold">Waktu</th>
                            <th class="px-6 py-3 text-right font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($this->recentTransactions as $trx)
                            <tr class="hover:bg-white/5 row-glow transition-colors duration-150">
                                <td class="px-6 py-3.5">
                                    <span class="font-mono text-xs text-text-muted">{{ $trx->invoice_number }}</span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-surface border border-border flex items-center justify-center text-[10px] font-bold text-text-muted">
                                            {{ substr($trx->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <span>{{ $trx->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    @if($trx->payment_method === 'cash')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-active/10 text-active border border-active/20">Cash</span>
                                    @elseif($trx->payment_method === 'qris')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gold/10 text-gold border border-gold/20">QRIS</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sidebar/10 text-sidebar border border-sidebar/20">Transfer</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-text-muted text-xs">
                                    {{ $trx->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-3.5 text-right font-bold">
                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-muted">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <p>Belum ada transaksi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ApexCharts CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>

    <script>
        function ownerDashboard() {
            return {
                chart: null,

                initChart() {
                    this.renderChart();

                    // Re-render chart when Livewire updates (period changes)
                    Livewire.hook('morph.updated', () => {
                        this.$nextTick(() => this.renderChart());
                    });
                },

                renderChart() {
                    const chartData = @json($this->chartData);

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    const options = {
                        series: [{
                            name: 'Pendapatan',
                            data: chartData.values
                        }],
                        chart: {
                            type: 'area',
                            height: 300,
                            fontFamily: 'Inter, sans-serif',
                            toolbar: { show: false },
                            background: 'transparent',
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 800,
                            }
                        },
                        colors: ['#C8973A'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.5,
                                opacityTo: 0.05,
                                stops: [0, 100],
                                colorStops: [
                                    { offset: 0, color: '#C8973A', opacity: 0.4 },
                                    { offset: 50, color: '#1B3A2D', opacity: 0.2 },
                                    { offset: 100, color: '#F0EDE8', opacity: 0.05 },
                                ]
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        dataLabels: { enabled: false },
                        xaxis: {
                            categories: chartData.labels,
                            labels: {
                                style: { colors: '#6B7B6E', fontSize: '11px' },
                                rotate: -45,
                                rotateAlways: chartData.labels.length > 14,
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#6B7B6E', fontSize: '11px' },
                                formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val),
                            }
                        },
                        grid: {
                            borderColor: '#D4CFC8',
                            strokeDashArray: 4,
                            xaxis: { lines: { show: false } },
                        },
                        tooltip: {
                            theme: 'light',
                            y: {
                                formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val),
                            },
                            style: { fontSize: '12px' },
                        },
                        theme: { mode: 'dark' },
                    };

                    const el = document.querySelector('#revenueChart');
                    if (el) {
                        this.chart = new ApexCharts(el, options);
                        this.chart.render();
                    }
                }
            };
        }
    </script>
</div>
