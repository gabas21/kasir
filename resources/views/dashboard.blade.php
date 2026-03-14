<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg overflow-hidden shadow">
                    <img src="/images/gemini-svg.svg" alt="Elite Cafe" class="w-full h-full object-cover">
                </div>
                <div>
                    <h2 class="font-bold text-xl text-text-main leading-tight">Dashboard</h2>
                    <p class="text-text-muted text-xs">Selamat datang, {{ auth()->user()->name }}!</p>
                </div>
            </div>
            <p class="text-text-muted text-sm hidden sm:block">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </x-slot>

    @php
        $todayStart = now()->startOfDay();
        $todayEnd   = now()->endOfDay();
        $yesterdayStart = now()->subDay()->startOfDay();
        $yesterdayEnd   = now()->subDay()->endOfDay();

        // Stat hari ini
        $todayRevenue = \App\Models\Transaction::where('status','paid')->whereBetween('created_at', [$todayStart, $todayEnd])->sum('total_price');
        $todayTrx     = \App\Models\Transaction::where('status','paid')->whereBetween('created_at', [$todayStart, $todayEnd])->count();
        $todayItems   = \App\Models\TransactionDetail::whereHas('transaction', fn($q) => $q->where('status','paid')->whereBetween('created_at', [$todayStart, $todayEnd]))->sum('qty');
        $todayDiscount = \App\Models\Transaction::where('status','paid')->whereBetween('created_at', [$todayStart, $todayEnd])->sum('discount_amount');

        // Stat kemarin (untuk perbandingan)
        $yesterdayRevenue = \App\Models\Transaction::where('status','paid')->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('total_price');
        $yesterdayTrx     = \App\Models\Transaction::where('status','paid')->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->count();

        // Perubahan persentase
        $revChange = $yesterdayRevenue > 0 ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) : ($todayRevenue > 0 ? 100 : 0);
        $trxChange = $yesterdayTrx > 0 ? round((($todayTrx - $yesterdayTrx) / $yesterdayTrx) * 100, 1) : ($todayTrx > 0 ? 100 : 0);

        // Rata-rata
        $avgTransaction = $todayTrx > 0 ? (int)($todayRevenue / $todayTrx) : 0;

        // Produk info
        $totalProducts = \App\Models\Product::count();
        $availableProducts = \App\Models\Product::where('is_available', true)->count();

        // Top 5 produk hari ini
        $topProducts = \App\Models\TransactionDetail::whereHas('transaction', fn($q) =>
            $q->where('status','paid')->whereBetween('created_at', [$todayStart, $todayEnd])
        )
            ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 8 transaksi terakhir
        $recentTransactions = \App\Models\Transaction::where('status','paid')
            ->with(['user','details'])
            ->latest()
            ->limit(8)
            ->get();

        // Pendapatan per metode bayar
        $paymentMethods = \App\Models\Transaction::where('status','paid')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_price) as total')
            ->groupBy('payment_method')
            ->get();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ===== STAT CARDS ROW ===== --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Pendapatan Hari Ini --}}
                <div class="bg-gradient-to-br from-amber-700 to-amber-900 rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -translate-y-6 translate-x-6"></div>
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-wider">Pendapatan Hari Ini</p>
                    <p class="text-2xl lg:text-3xl font-extrabold mt-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                    <div class="flex items-center gap-1 mt-2 text-sm">
                        @if($revChange >= 0)
                            <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <span class="text-green-300 font-semibold">+{{ $revChange }}%</span>
                        @else
                            <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            <span class="text-red-300 font-semibold">{{ $revChange }}%</span>
                        @endif
                        <span class="text-white/50 text-xs">vs kemarin</span>
                    </div>
                </div>

                {{-- Total Transaksi --}}
                <div class="bg-white border border-border rounded-xl p-5 shadow-sm">
                    <p class="text-text-muted text-xs font-semibold uppercase tracking-wider">Transaksi</p>
                    <p class="text-text-main font-extrabold text-2xl lg:text-3xl mt-1">{{ $todayTrx }}</p>
                    <div class="flex items-center gap-1 mt-2 text-sm">
                        @if($trxChange >= 0)
                            <span class="text-green-600 font-semibold text-xs bg-green-50 px-1.5 py-0.5 rounded-full">+{{ $trxChange }}%</span>
                        @else
                            <span class="text-red-600 font-semibold text-xs bg-red-50 px-1.5 py-0.5 rounded-full">{{ $trxChange }}%</span>
                        @endif
                        <span class="text-text-muted text-xs">vs kemarin</span>
                    </div>
                </div>

                {{-- Rata-rata Transaksi --}}
                <div class="bg-white border border-border rounded-xl p-5 shadow-sm">
                    <p class="text-text-muted text-xs font-semibold uppercase tracking-wider">Rata-rata / Trx</p>
                    <p class="text-text-main font-extrabold text-2xl lg:text-3xl mt-1">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</p>
                    <p class="text-text-muted text-xs mt-2">{{ $todayItems }} item terjual hari ini</p>
                </div>

                {{-- Produk --}}
                <div class="bg-white border border-border rounded-xl p-5 shadow-sm">
                    <p class="text-text-muted text-xs font-semibold uppercase tracking-wider">Produk</p>
                    <p class="text-text-main font-extrabold text-2xl lg:text-3xl mt-1">{{ $availableProducts }}<span class="text-text-muted text-base font-normal"> / {{ $totalProducts }}</span></p>
                    <p class="text-text-muted text-xs mt-2">tersedia dari total</p>
                </div>
            </div>

            {{-- ===== MIDDLE ROW: Top Products + Payment Methods ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Top Produk Hari Ini --}}
                <div class="lg:col-span-2 bg-white border border-border rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-border flex justify-between items-center">
                        <h3 class="font-bold text-text-main">🏆 Produk Terlaris Hari Ini</h3>
                        <a href="{{ route('reports.index') }}" class="text-gold text-xs font-semibold hover:underline">Lihat Semua →</a>
                    </div>
                    @if($topProducts->count() > 0)
                        <div class="divide-y divide-border">
                            @foreach($topProducts as $i => $product)
                                <div class="flex items-center justify-between px-5 py-3.5 hover:bg-cream/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                                            {{ $i === 0 ? 'bg-gold/10 text-gold' : ($i === 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-50 text-orange-500') }}">
                                            {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : ($i + 1))) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-text-main text-sm">{{ $product->product_name }}</p>
                                            <p class="text-text-muted text-xs">{{ $product->total_qty }} porsi terjual</p>
                                        </div>
                                    </div>
                                    <p class="font-bold text-gold text-sm">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-10 text-center text-text-muted text-sm">
                            Belum ada transaksi hari ini.
                        </div>
                    @endif
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-white border border-border rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-border">
                        <h3 class="font-bold text-text-main">💳 Metode Pembayaran</h3>
                    </div>
                    @if($paymentMethods->count() > 0)
                        <div class="p-5 space-y-4">
                            @foreach($paymentMethods as $pm)
                                @php
                                    $pct = $todayTrx > 0 ? round(($pm->count / $todayTrx) * 100) : 0;
                                    $color = match($pm->payment_method) { 'cash' => 'bg-green-500', 'qris' => 'bg-blue-500', 'transfer' => 'bg-purple-500', default => 'bg-gray-400' };
                                    $label = match($pm->payment_method) { 'cash' => '💵 Tunai', 'qris' => '📱 QRIS', 'transfer' => '🏦 Transfer', default => ucfirst($pm->payment_method) };
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-sm font-medium text-text-main">{{ $label }}</span>
                                        <span class="text-xs text-text-muted font-semibold">{{ $pm->count }} trx ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-cream rounded-full h-2.5">
                                        <div class="{{ $color }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <p class="text-right text-xs text-text-muted mt-1">Rp {{ number_format($pm->total, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-10 text-center text-text-muted text-sm">
                            Belum ada data pembayaran hari ini.
                        </div>
                    @endif

                    {{-- Diskon info --}}
                    @if($todayDiscount > 0)
                        <div class="mx-5 mb-4 px-4 py-3 bg-gold/5 border border-gold/10 rounded-xl">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-text-main font-medium">🎫 Total Diskon</span>
                                <span class="text-gold font-bold text-sm">-Rp {{ number_format($todayDiscount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== RECENT TRANSACTIONS TABLE ===== --}}
            <div class="bg-white border border-border rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-border flex justify-between items-center">
                    <h3 class="font-bold text-text-main">📋 Transaksi Terakhir</h3>
                    <a href="{{ route('reports.index') }}" class="text-gold text-xs font-semibold hover:underline">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-cream/80 text-text-muted text-xs uppercase tracking-wider">
                                <th class="px-5 py-3 text-left font-semibold">Invoice</th>
                                <th class="px-5 py-3 text-left font-semibold">Waktu</th>
                                <th class="px-5 py-3 text-left font-semibold">Kasir</th>
                                <th class="px-5 py-3 text-left font-semibold">Item</th>
                                <th class="px-5 py-3 text-center font-semibold">Bayar</th>
                                <th class="px-5 py-3 text-right font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($recentTransactions as $trx)
                                <tr class="hover:bg-cream/30 transition-colors">
                                    <td class="px-5 py-3 font-mono text-xs text-text-muted">{{ $trx->invoice_number }}</td>
                                    <td class="px-5 py-3 text-text-muted">{{ $trx->created_at->format('d/m H:i') }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                            {{ $trx->user->name ?? 'System' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-text-muted text-xs max-w-[200px] truncate">
                                        {{ $trx->details->map(fn($d) => $d->product_name)->implode(', ') }}
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @php $badgeColor = match($trx->payment_method) { 'cash' => 'bg-green-50 text-green-700 border-green-200', 'qris' => 'bg-blue-50 text-blue-700 border-blue-200', 'transfer' => 'bg-purple-50 text-purple-700 border-purple-200', default => 'bg-gray-50 text-gray-700 border-gray-200' }; @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $badgeColor }}">{{ strtoupper($trx->payment_method) }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-text-main">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-text-muted">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ===== QUICK ACTIONS ===== --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                <a href="{{ route('pos.index') }}" class="flex flex-col items-center gap-2 bg-gradient-to-br from-amber-600 to-amber-800 text-white rounded-xl px-4 py-5 font-semibold hover:opacity-90 active:scale-95 transition-all shadow-lg text-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="text-sm">Buka POS</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-2 bg-white text-text-main rounded-xl px-4 py-5 font-semibold hover:bg-cream active:scale-95 transition-all border border-border text-center">
                    <svg class="w-7 h-7 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="text-sm">Laporan</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-2 bg-white text-text-main rounded-xl px-4 py-5 font-semibold hover:bg-cream active:scale-95 transition-all border border-border text-center">
                    <svg class="w-7 h-7 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="text-sm">Produk</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex flex-col items-center gap-2 bg-white text-text-main rounded-xl px-4 py-5 font-semibold hover:bg-cream active:scale-95 transition-all border border-border text-center">
                    <svg class="w-7 h-7 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="text-sm">Users</span>
                </a>
                <a href="{{ route('promos.index') }}" class="flex flex-col items-center gap-2 bg-white text-text-main rounded-xl px-4 py-5 font-semibold hover:bg-cream active:scale-95 transition-all border border-border text-center">
                    <svg class="w-7 h-7 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span class="text-sm">Promo</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
