<div class="py-8 text-text-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header & Filters --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="animate-fade-in-up">
                <h2 class="text-2xl font-bold">Laporan Penjualan</h2>
                <p class="text-text-muted mt-1">Ringkasan transaksi dan pendapatan toko.</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Export Buttons --}}
                <div class="flex gap-2">
                    <a href="{{ route('export.pdf', ['range' => $dateRange, 'from' => $dateFrom, 'to' => $dateTo]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-danger/10 text-danger border border-danger/20 rounded-lg text-sm font-semibold hover:bg-danger hover:text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        PDF
                    </a>
                    <a href="{{ route('export.csv', ['range' => $dateRange, 'from' => $dateFrom, 'to' => $dateTo]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-active/10 text-active border border-active/20 rounded-lg text-sm font-semibold hover:bg-active hover:text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        Excel
                    </a>
                </div>
            </div>
            <div class="bg-surface p-1 rounded-lg border border-border inline-flex gap-1" x-data="{ range: @entangle('dateRange').live }">
                <button wire:click="$set('dateRange', 'today')" 
                        :class="range === 'today' ? 'bg-gold text-white font-medium' : 'text-text-muted hover:text-text-main hover:bg-cream'"
                        class="px-4 py-2 rounded-md text-sm transition-colors duration-150">
                    Hari Ini
                </button>
                <button wire:click="$set('dateRange', '7days')"
                        :class="range === '7days' ? 'bg-gold text-white font-medium' : 'text-text-muted hover:text-text-main hover:bg-cream'" 
                        class="px-4 py-2 rounded-md text-sm transition-colors duration-150">
                    7 Hari
                </button>
                <button wire:click="$set('dateRange', '30days')"
                        :class="range === '30days' ? 'bg-gold text-white font-medium' : 'text-text-muted hover:text-text-main hover:bg-cream'" 
                        class="px-4 py-2 rounded-md text-sm transition-colors duration-150">
                    30 Hari
                </button>
                <button wire:click="$set('dateRange', 'custom')"
                        :class="range === 'custom' ? 'bg-gold text-white font-medium' : 'text-text-muted hover:text-text-main hover:bg-cream'" 
                        class="px-4 py-2 rounded-md text-sm transition-colors duration-150 border border-transparent"
                        :class="range === 'custom' ? 'border-gold' : ''">
                    Custom
                </button>
            </div>
        </div>

        {{-- Custom Date Picker --}}
        @if($dateRange === 'custom')
        <div class="bg-surface p-4 rounded-lg border border-border mb-8 flex flex-wrap gap-4 items-end animate-fade-in-up">
            <div>
                <label class="block text-xs font-semibold text-text-muted uppercase tracking-wider mb-2">Dari Tanggal</label>
                <input type="date" wire:model.live="dateFrom" class="bg-cream border border-border text-text-main rounded-lg focus:ring-gold focus:border-gold">
            </div>
            <div>
                <label class="block text-xs font-semibold text-text-muted uppercase tracking-wider mb-2">Sampai Tanggal</label>
                <input type="date" wire:model.live="dateTo" class="bg-cream border border-border text-text-main rounded-lg focus:ring-gold focus:border-gold">
            </div>
        </div>
        @endif

        {{-- Loading Indicator Overlay --}}
        <div wire:loading.delay.longer class="fixed inset-0 z-50 bg-sidebar/80 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-surface p-5 rounded-lg border border-border text-center shadow-xl">
                <svg class="animate-spin h-8 w-8 text-gold mx-auto mb-3" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-text-muted font-medium">Memproses Data...</span>
            </div>
        </div>

        {{-- Summary Cards (4 cols) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {{-- Pendapatan --}}
            <div class="bg-surface p-5 rounded-lg border border-border">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-gold/20 flex items-center justify-center text-gold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-text-muted uppercase tracking-wider">Total Pendapatan</span>
                </div>
                <h3 class="text-3xl font-bold text-gold">Rp {{ number_format($this->totalRevenue, 0, ',', '.') }}</h3>
            </div>

            {{-- Transaksi --}}
            <div class="bg-surface p-5 rounded-lg border border-border">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-cream border border-border flex items-center justify-center text-text-main">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-text-muted uppercase tracking-wider">Total Transaksi</span>
                </div>
                <h3 class="text-3xl font-bold">{{ number_format($this->totalTransactions) }}</h3>
            </div>

            {{-- Avg per Transaksi --}}
            <div class="bg-surface p-5 rounded-lg border border-border">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-cream border border-border flex items-center justify-center text-text-main">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-text-muted uppercase tracking-wider">Rata-rata/Transaksi</span>
                </div>
                <h3 class="text-3xl font-bold">Rp {{ number_format($this->averageTransaction, 0, ',', '.') }}</h3>
            </div>

            {{-- Item Terjual --}}
            <div class="bg-surface p-5 rounded-lg border border-border">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-cream border border-border flex items-center justify-center text-text-main">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-text-muted uppercase tracking-wider">Item Terjual</span>
                </div>
                <h3 class="text-3xl font-bold">{{ number_format($this->totalItemsSold) }} <span class="text-lg text-text-muted font-normal">Pcs</span></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- Left Side: Payment Breakdown --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Payment Methods --}}
                <div class="bg-surface border border-border rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-border">
                        <h3 class="font-bold text-lg">Metode Pembayaran</h3>
                    </div>
                    <div class="p-5 space-y-5">
                        @forelse ($this->paymentBreakdown as $payment)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium flex items-center gap-2">
                                        @if($payment['key'] == 'cash')
                                            <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        @elseif($payment['key'] == 'qris')
                                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        @endif
                                        {{ $payment['method'] }}
                                    </span>
                                    <span class="text-sm font-semibold text-text-muted">{{ $payment['percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-cream rounded-full h-2 mb-1">
                                    <div class="bg-gold h-2 rounded-full" style="width: {{ $payment['percentage'] . '%' }};"></div>
                                </div>
                                <div class="flex justify-between text-xs text-text-muted">
                                    <span>{{ $payment['count'] }} TRX</span>
                                    <span>Rp {{ number_format($payment['total'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-text-muted text-center py-2">Belum ada data pembayaran.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Top 5 Produk --}}
                <div class="bg-surface border border-border rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-border">
                        <h3 class="font-bold text-lg">Top 5 Produk Terlaris</h3>
                    </div>
                    <div class="divide-y divide-border">
                        @forelse ($this->topProducts as $index => $product)
                            <div class="p-4 flex items-center gap-4 hover:bg-cream transition-colors">
                                <div class="w-8 h-8 rounded-full bg-cream border border-border flex items-center justify-center font-bold text-sm text-text-muted">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium truncate">{{ $product['product_name'] }}</p>
                                    <p class="text-xs text-gold">{{ $product['total_qty'] }} Pcs</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-sm">Rp {{ number_format($product['total_revenue'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center text-text-muted text-sm border-t border-border">
                                Belum ada produk terjual dalam rentang waktu ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Side: Transactions List --}}
            <div class="lg:col-span-2">
                <div class="bg-surface border border-border rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-border flex justify-between items-center">
                        <h3 class="font-bold text-lg">Riwayat Transaksi Terakhir</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-text-muted uppercase bg-cream border-b border-border">
                                <tr>
                                    <th scope="col" class="px-5 py-3 font-semibold">Invoice & Tgl</th>
                                    <th scope="col" class="px-5 py-3 font-semibold">Kasir</th>
                                    <th scope="col" class="px-5 py-3 font-semibold">Metode</th>
                                    <th scope="col" class="px-5 py-3 text-right font-semibold">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @forelse ($transactions as $trx)
                                    <tr class="hover:bg-cream transition-colors duration-150">
                                        <td class="px-5 py-4">
                                            <div class="font-medium text-text-main">{{ $trx->invoice_number }}</div>
                                            <div class="text-xs text-text-muted mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-cream flex items-center justify-center text-[10px] font-bold text-text-muted">
                                                    {{ substr($trx->user->name ?? '?', 0, 1) }}
                                                </div>
                                                <span class="text-text-main">{{ $trx->user->name ?? 'Kasir / System' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4">
                                            @if($trx->payment_method === 'cash')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-active/10 text-active border border-active/20">Cash</span>
                                            @elseif($trx->payment_method === 'qris')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gold/10 text-gold border border-gold/20">QRIS</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sidebar/10 text-sidebar border border-sidebar/20">Transfer</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <span class="font-bold text-text-main">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-10 text-center text-text-muted">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 mb-3 text-border" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                <p>Tidak ada transaksi pada rentang waktu ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4">
                    {{ $transactions->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>

    </div>
</div>
