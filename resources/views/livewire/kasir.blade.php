<div class="flex h-[calc(100vh-theme(spacing.24))] bg-cream overflow-hidden relative w-full rounded-lg border border-border" x-data="{ mobileCartOpen: false }">

    {{-- Overlay untuk Mobile --}}
    <div x-show="mobileCartOpen" style="display: none;"
         @click="mobileCartOpen = false"
         class="fixed inset-0 bg-black/60 z-40 lg:hidden backdrop-blur-sm"
         x-transition.opacity></div>

    {{-- ======================================
         SIDEBAR KIRI (KERANJANG) — Bisa di-toggle di Mobile
    ====================================== --}}
    <aside :class="mobileCartOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-80 lg:w-80 flex flex-col bg-surface border-r border-border shrink-0 transition-transform duration-300 lg:static lg:translate-x-0 h-full shadow-lg lg:shadow-none">

        {{-- Header Sidebar --}}
        <div class="px-4 py-4 border-b border-border bg-surface">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-text-main font-bold text-base">Pesanan Baru</h2>
                    <p class="text-text-muted text-xs">{{ date('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @if(count($this->cart) > 0)
                        <button wire:click="clearCart"
                                class="text-danger text-xs hover:text-danger/80 transition-colors duration-150 font-medium">
                            Kosongkan
                        </button>
                    @endif
                    <button @click="mobileCartOpen = false" class="lg:hidden text-text-muted hover:text-text-main p-1.5 rounded-lg bg-cream border border-border">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Flash Message --}}
        @if(session()->has('message'))
            <div class="mx-4 mt-3 px-3 py-2 rounded-lg bg-active/10 border border-active/30 text-active text-sm font-medium toast-enter">
                {{ session('message') }}
            </div>
        @endif

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-2">
            @forelse ($this->cart as $productId => $item)
                <div class="flex items-center gap-3 bg-cream rounded-lg px-3 py-2.5 border border-border group transition-all hover:border-gold/30">
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-text-main text-sm font-medium truncate">{{ $item['name'] }}</p>
                        <p class="text-gold text-xs font-semibold mt-0.5">
                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Qty Stepper --}}
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button aria-label="Kurangi jumlah barang" wire:click="decrement({{ $item['id'] }})"
                                class="w-7 h-7 rounded bg-cream hover:bg-danger/10 hover:text-danger
                                       text-text-muted flex items-center justify-center
                                       transition-all duration-150 active:scale-90 text-sm font-bold border border-border">
                            −
                        </button>
                        <span class="w-6 text-center text-text-main font-semibold text-sm">{{ $item['qty'] }}</span>
                        <button aria-label="Tambah jumlah barang" wire:click="increment({{ $item['id'] }})"
                                class="w-7 h-7 rounded bg-cream hover:bg-gold/10 hover:text-gold
                                       text-text-muted flex items-center justify-center
                                       transition-all duration-150 active:scale-90 text-sm font-bold border border-border">
                            +
                        </button>
                    </div>

                    {{-- Subtotal --}}
                    <div class="text-right shrink-0">
                        <p class="text-text-main text-xs font-medium">
                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center h-48 text-center">
                    <svg class="w-12 h-12 text-text-muted opacity-30 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-text-muted text-sm">Pilih menu dari kanan</p>
                    <p class="text-text-muted text-xs opacity-60 mt-1">Ketuk produk untuk menambahkan</p>
                </div>
            @endforelse
        </div>

        {{-- Footer — Total & Bayar --}}
        <div class="px-4 pb-4 pt-3 border-t border-border space-y-3 shrink-0">
            {{-- Ringkasan --}}
            <div class="space-y-1.5">
                <div class="flex justify-between text-sm">
                    <span class="text-xs text-text-muted">{{ $this->itemCount }} item</span>
                    <span class="text-text-muted">Subtotal</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-text-main font-bold text-lg">Total</span>
                    <span class="text-gold font-bold text-xl">
                        Rp {{ number_format($this->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Tombol Bayar --}}
            <button
                type="button"
                wire:click="openPaymentModal"
                wire:loading.attr="disabled"
                @disabled($this->isCartEmpty)
                class="w-full py-4 rounded-xl font-bold text-base transition-all duration-150 active:scale-95
                       flex items-center justify-center gap-2
                        {{ !$this->isCartEmpty
                           ? 'bg-gold text-white hover:opacity-90 cursor-pointer shadow-md'
                           : 'bg-cream text-text-muted cursor-not-allowed' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>BAYAR SEKARANG</span>
            </button>
        </div>
    </aside>

    {{-- ======================================
         SIDEBAR KATEGORI (TABLET/DESKTOP)
    ====================================== --}}
    <aside class="hidden md:flex flex-col w-20 lg:w-24 bg-sidebar border-r border-sidebar/20 shrink-0 h-full">
        {{-- Logo kecil --}}
        <div class="flex items-center justify-center py-4 border-b border-white/10">
            <div class="w-10 h-10 rounded-lg overflow-hidden shadow-lg">
                <img src="/images/gemini-svg.svg" alt="Elite Cafe" class="w-full h-full object-cover">
            </div>
        </div>

        {{-- Kategori Vertikal --}}
        <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-1.5">
            <button wire:click="$set('selectedCategory', 'all')"
                    class="w-full flex flex-col items-center gap-1 px-1 py-3 rounded-xl text-center transition-all duration-150
                           {{ $selectedCategory === 'all' ? 'bg-gold text-white shadow-md' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="text-[10px] lg:text-xs font-bold uppercase leading-tight">Semua</span>
            </button>
            @foreach($this->categories() as $name => $slug)
                <button wire:key="vcat-{{ $slug }}" wire:click="$set('selectedCategory', '{{ $slug }}')"
                        class="w-full flex flex-col items-center gap-1 px-1 py-3 rounded-xl text-center transition-all duration-150
                               {{ $selectedCategory === $slug ? 'bg-gold text-white shadow-md' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span class="text-[10px] lg:text-xs font-bold uppercase leading-tight truncate w-full">{{ $name }}</span>
                </button>
            @endforeach
        </nav>

        {{-- User Info --}}
        <div class="border-t border-white/10 px-2 py-3 flex flex-col items-center gap-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-danger bg-danger/10 hover:bg-danger/20 p-2 rounded-xl transition-colors duration-150 border border-danger/30" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- ======================================
         AREA KANAN — MENU PRODUK
    ====================================== --}}
    <main class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto relative">

        {{-- Header Area Menu (Mobile: logo+categories | Tablet: hanya info) --}}
        <header class="px-5 py-4 border-b border-sidebar/10 flex items-center justify-between shrink-0 bg-sidebar">
            {{-- Mobile: logo + categories --}}
            <div class="flex items-center gap-3 md:hidden">
                <div class="w-9 h-9 rounded-lg overflow-hidden shadow-lg">
                    <img src="/images/gemini-svg.svg" alt="Elite Cafe" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-white font-bold text-base leading-tight">Elite Cafe</h1>
                    <p class="text-white/60 text-xs">Kasir Cafe</p>
                </div>
            </div>

            {{-- Mobile: Horizontal Category Tabs --}}
            <nav class="flex md:hidden items-center gap-1.5 overflow-x-auto">
                <button wire:click="$set('selectedCategory', 'all')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider whitespace-nowrap transition-all duration-150
                               {{ $selectedCategory === 'all' ? 'bg-gold text-white' : 'text-text-muted hover:text-text-main hover:bg-cream' }}">
                    Semua
                </button>
                @foreach($this->categories() as $name => $slug)
                    <button wire:key="cat-{{ $slug }}" wire:click="$set('selectedCategory', '{{ $slug }}')"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider whitespace-nowrap transition-all duration-150
                                   {{ $selectedCategory === $slug ? 'bg-gold text-white shadow-md' : 'text-white/60 hover:text-white hover:bg-gold/10' }}">
                        {{ $name }}
                    </button>
                @endforeach
            </nav>

            {{-- Tablet/Desktop: Nama toko + Info kasir --}}
            <div class="hidden md:flex items-center gap-3">
                <div>
                    <h1 class="text-white font-bold text-base leading-tight">Elite Cafe</h1>
                    <p class="text-white/60 text-xs">Kasir Cafe</p>
                </div>
            </div>

            {{-- Info Kasir & Logout --}}
            <div class="text-right flex items-center gap-4">
                <div class="hidden md:block text-right">
                    <p class="text-white text-sm font-medium">{{ auth()->check() ? auth()->user()->name : 'Kasir' }}</p>
                    <p class="text-white/60 text-xs">{{ date('H:i') }} WIB</p>
                </div>
                {{-- Logout button only on mobile header (tablet has it in sidebar) --}}
                <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                    @csrf
                    <button type="submit" class="text-danger bg-danger/10 hover:bg-danger/20 p-2 rounded-xl transition-colors duration-150 border border-danger/30" title="Logout">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- Grid Produk (scrollable) --}}
        <div class="flex-1 overflow-y-auto p-4 pb-28 lg:pb-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($this->filteredProducts() as $product)
                    <x-product-card
                        wire:key="prod-{{ $product->id }}"
                        :id="$product->id"
                        :name="$product->name"
                        :price="$product->price"
                        :category="$product->category->name ?? 'Lainnya'"
                        :image="$product->image ? 'storage/' . $product->image : null"
                        :available="$product->is_available"
                    />
                @endforeach
            </div>

            @if(count($this->filteredProducts()) === 0)
                <div class="flex flex-col items-center justify-center h-64 text-center">
                    <p class="text-text-muted">Tidak ada produk di kategori ini.</p>
                </div>
            @endif
        </div>
    </main>

    {{-- ======================================
         FLOATING CART BUTTON (MOBILE ONLY)
    ====================================== --}}
    @if(count($this->cart) > 0)
    <div class="lg:hidden fixed bottom-5 left-5 right-5 bg-gold text-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.3)] p-4 flex justify-between items-center z-30 cursor-pointer active:scale-95 transition-transform border border-gold-hover animate-fade-in-up animate-pulse-glow" 
         @click="mobileCartOpen = true">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-white/20 rounded-full flex items-center justify-center font-bold text-base shadow-inner border border-white/10">
                {{ $this->itemCount }}
            </div>
            <div>
                <p class="font-bold text-sm tracking-wide">Lihat Keranjang</p>
                <p class="text-xs opacity-90 font-medium">Lanjut Pembayaran</p>
            </div>
        </div>
        <div class="font-bold text-lg flex items-center gap-1">
            Rp {{ number_format($this->total, 0, ',', '.') }}
            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </div>
    </div>
    @endif

{{-- ======================================
     MODAL PEMBAYARAN
====================================== --}}
@if($showPaymentModal)
    <div wire:key="payment-modal-container" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm print:hidden">
        <div class="bg-surface border border-sidebar/20 w-full max-w-lg rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            {{-- Header Modal --}}
            <div class="px-6 py-4 border-b border-border flex justify-between items-center bg-cream shrink-0">
                <h3 class="text-text-main font-bold text-lg">Pilih Pembayaran</h3>
                <button wire:click="closePaymentModal" class="text-text-muted hover:text-danger p-1 rounded-lg hover:bg-surface-3 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="p-6 overflow-y-auto w-full">
                {{-- Total Tagihan Info --}}
                <div class="bg-cream rounded-lg p-4 mb-6 border border-border flex justify-between items-center">
                    <span class="text-text-muted font-medium">Total Tagihan</span>
                    <span class="text-gold font-bold text-2xl">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>

                @if(session()->has('payment_error'))
                    <div class="mb-4 px-3 py-2 rounded-lg bg-danger/10 border border-danger/30 text-danger text-sm font-medium">
                        {{ session('payment_error') }}
                    </div>
                @endif

                {{-- Opsi Metode Bayar --}}
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <button wire:click="$set('paymentMethod', 'cash')" class="py-3 rounded-lg border flex flex-col items-center justify-center gap-2 transition-all duration-150 {{ $paymentMethod === 'cash' ? 'bg-gold/10 border-gold text-gold' : 'bg-cream border-transparent text-text-muted hover:border-border' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="font-semibold text-sm">Tunai</span>
                    </button>
                    <button wire:click="$set('paymentMethod', 'qris')" class="py-3 rounded-lg border flex flex-col items-center justify-center gap-2 transition-all duration-150 {{ $paymentMethod === 'qris' ? 'bg-gold/10 border-gold text-gold' : 'bg-cream border-transparent text-text-muted hover:border-border' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span class="font-semibold text-sm">QRIS</span>
                    </button>
                    <button wire:click="$set('paymentMethod', 'transfer')" class="py-3 rounded-lg border flex flex-col items-center justify-center gap-2 transition-all duration-150 {{ $paymentMethod === 'transfer' ? 'bg-gold/10 border-gold text-gold' : 'bg-cream border-transparent text-text-muted hover:border-border' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <span class="font-semibold text-sm">Transfer</span>
                    </button>
                </div>

                {{-- Promo Code --}}
                <div class="mb-4">
                    <label class="block text-text-muted text-xs font-semibold uppercase tracking-wider mb-2">Kode Promo</label>
                    <div class="flex gap-2">
                        <input type="text" wire:model="promoCode" placeholder="Masukkan kode promo"
                               style="text-transform: uppercase"
                               class="flex-1 bg-cream border border-border rounded-lg py-2.5 px-4 text-sm text-text-main placeholder:text-text-muted/50 focus:ring-2 focus:ring-gold/50 focus:border-gold"
                               {{ $promoValid ? 'disabled' : '' }}>
                        @if($promoValid)
                            <button aria-label="Hapus kode promo" wire:click="removePromo" wire:loading.attr="disabled" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-danger/10 text-danger border border-danger/20 hover:bg-danger hover:text-white transition-all">
                                <span wire:loading.remove wire:target="removePromo">Hapus</span>
                                <span wire:loading wire:target="removePromo">...</span>
                            </button>
                        @else
                            <button aria-label="Gunakan kode promo" wire:click="applyPromo" wire:loading.attr="disabled" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-gold/10 text-gold border border-gold/20 hover:bg-gold hover:text-white transition-all">
                                <span wire:loading.remove wire:target="applyPromo">Pakai</span>
                                <span wire:loading wire:target="applyPromo">...</span>
                            </button>
                        @endif
                    </div>
                    @if($promoMessage)
                        <p class="text-xs mt-1.5 {{ $promoValid ? 'text-success' : 'text-danger' }}">
                            {{ $promoValid ? '✅' : '❌' }} {{ $promoMessage }}
                        </p>
                    @endif
                    @if($discountAmount > 0)
                        <div class="mt-2 flex justify-between items-center text-sm bg-gold/5 border border-gold/10 rounded-lg px-3 py-2">
                            <span class="font-bold text-text-main">Subtotal</span>
                    <span class="font-semibold text-text-main">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm bg-gold/5 border border-gold/10 rounded-lg px-3 py-2 mt-1">
                            <span class="text-gold font-medium">🎫 Diskon</span>
                            <span class="text-gold font-bold">-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Area Input Dinamis Berdasarkan Metode Bayar --}}
                <div class="h-48"> {{-- Fixed height untuk mencegah loncat --}}
                    @if($paymentMethod === 'cash')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-text-muted text-sm font-medium mb-1.5">Uang Diterima</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-muted font-bold">Rp</span>
                                    <input type="number" wire:model.live.debounce.300ms="paid_amount" class="w-full bg-cream border border-border rounded-lg py-3 pl-12 pr-4 text-text-main font-bold text-lg focus:ring-2 focus:ring-gold/50 focus:border-gold" placeholder="0">
                                </div>
                            </div>
                            
                            {{-- Kembalian --}}
                            <div class="flex justify-between items-center p-3 bg-gold/10 rounded-xl border border-border w-full">
                                <span class="text-text-muted font-medium">Kembalian</span>
                                <span class="font-bold text-lg {{ $this->changeAmount > 0 ? 'text-gold' : 'text-text-main' }}">
                                    Rp {{ number_format($this->changeAmount, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Suggest UI Mute Notes --}}
                            <div>
                                <input type="text" wire:model="notes" placeholder="Catatan opsional (Contoh: Tanpa bawang)" class="w-full bg-cream border border-border rounded-lg py-2.5 px-4 text-sm text-text-main mt-1">
                            </div>
                        </div>

                    @elseif($paymentMethod === 'qris')
                        <div class="flex flex-col items-center justify-center h-full space-y-3">
                            <div class="w-32 h-32 bg-white rounded-lg flex items-center justify-center p-2 mb-2">
                                {{-- Placeholder QR --}}
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=ELITECAFE-{{ time() }}" alt="QRIS" class="w-full h-full object-cover">
                            </div>
                            <p class="text-text-muted text-sm text-center">Minta pelanggan scan QR Code di atas menggunakan e-Wallet atau M-Banking.</p>
                        </div>
                    
                    @elseif($paymentMethod === 'transfer')
                        <div class="flex flex-col h-full justify-center space-y-4">
                            <div class="bg-cream rounded-lg p-4 border border-border text-center space-y-1">
                                <p class="text-text-muted text-sm">Transfer ke Rekening BNI</p>
                                <p class="text-text-main font-mono font-bold text-xl tracking-wider">123-456-7890</p>
                                <p class="text-sm text-text-main">a.n KasirCafe Owner</p>
                            </div>
                            <p class="text-text-muted text-sm text-center px-4">Pastikan uang sudah masuk ke rekening sebelum klik Konfirmasi Bayar.</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Footer Modal --}}
            <div class="px-6 py-4 border-t border-border bg-cream shrink-0 flex gap-3">
                <button aria-label="Tutup modal pembayaran" wire:click="closePaymentModal" class="flex-1 py-3 rounded-xl font-semibold bg-surface-3 text-text-main hover:bg-surface-hover transition-colors">
                    Batal
                </button>
                <button aria-label="Konfirmasi pembayaran" wire:click="checkout" wire:loading.attr="disabled" class="flex-1 py-3 rounded-lg font-bold bg-gold text-white hover:opacity-90 transition-colors flex items-center justify-center gap-2 shadow-md">
                    <span wire:loading.remove wire:target="checkout" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Konfirmasi Bayar
                    </span>
                    <span wire:loading wire:target="checkout" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>
@endif

{{-- ======================================
     MODAL STRUK (RECEIPT)
====================================== --}}
@if($showReceiptModal && $this->currentTransaction)
    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm print:bg-white print:static print:h-auto print:w-auto overflow-y-auto w-full">
        
        <div class="relative w-full max-w-sm mx-auto my-8 print:m-0 print:shadow-none">
            {{-- Tombol Aksi di Luar Struk (Non-Print) --}}
            <div class="flex gap-2 justify-end mb-4 print:hidden absolute -top-12 right-0">
                <button onclick="window.print()" class="px-4 py-2 bg-gold text-white font-semibold rounded-lg hover:opacity-90 transition shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
                <button wire:click="closeReceiptModal" class="px-4 py-2 bg-surface text-text-main font-semibold rounded-lg hover:bg-surface-hover border border-border transition shadow-lg">
                    Tutup
                </button>
            </div>

            {{-- Kertas Struk --}}
            <div class="bg-white text-black p-6 rounded-b-lg rounded-t flex flex-col font-mono text-sm leading-tight shadow-2xl print:shadow-none border-t-8 border-gold print:border-none relative zigzag-bottom">
                
                {{-- Header Struk --}}
                <div class="text-center mb-4">
                    <img src="/images/gemini-svg.svg" alt="Elite Cafe" class="w-16 h-16 mx-auto mb-2 rounded-full">
                    <h2 class="font-bold text-xl uppercase mb-1">Elite Cafe</h2>
                    <p class="text-xs text-gray-600">Jl. Suka Ngopi No. 123, Kota Anda</p>
                    <p class="text-xs text-gray-600">Telp: 0812-3456-7890</p>
                </div>
                
                <div class="border-b-2 border-dashed border-gray-300 pb-2 mb-2 flex justify-between text-xs text-gray-600">
                    <div>
                        <p>{{ date('d/m/Y H:i', strtotime($this->currentTransaction->created_at)) }}</p>
                        <p>Kasir: {{ $this->currentTransaction->user->name ?? 'Kasir' }}</p>
                    </div>
                    <div class="text-right">
                        <p>{{ $this->currentTransaction->invoice_number }}</p>
                        <p class="uppercase">{{ $this->currentTransaction->payment_method }}</p>
                    </div>
                </div>

                <div class="mb-2">
                    <table class="w-full text-xs">
                        @foreach($this->currentTransaction->details as $detail)
                            <tr>
                                <td colspan="3" class="pb-1 font-semibold">{{ $detail->product_name }}</td>
                            </tr>
                            <tr>
                                <td class="w-8 text-center text-gray-600">{{ $detail->qty }}x</td>
                                <td class="text-gray-600">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="border-t-2 border-dashed border-gray-300 pt-2 mb-4">
                    <div class="flex justify-between font-bold text-base mb-1">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($this->currentTransaction->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($this->currentTransaction->payment_method === 'cash')
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>TUNAI</span>
                            <span>Rp {{ number_format($this->currentTransaction->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>KEMBALI</span>
                            <span>Rp {{ number_format($this->currentTransaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <div class="text-center text-xs text-gray-500 mt-auto">
                    <p>Terima Kasih Atas Kunjungan Anda!</p>
                    <p class="mt-1">WiFi: EliteCafe_Guest / Pass: kopienak</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk cetak otomatis opsional --}}
    <!-- <script> setTimeout(() => window.print(), 500); </script> -->
@endif


</div> {{-- Closing Root Livewire Div --}}

<style>
    /* Efek kertas struk zigzag bawah */
    .zigzag-bottom {
        -webkit-mask-image: linear-gradient(to bottom, black calc(100% - 4px), transparent calc(100% - 4px)), radial-gradient(4px at 50% bottom, transparent 49%, black 51%);
        -webkit-mask-size: 100% 100%, 8px 8px;
        -webkit-mask-repeat: no-repeat, repeat-x;
        -webkit-mask-position: top, bottom;
        mask-image: linear-gradient(to bottom, black calc(100% - 4px), transparent calc(100% - 4px)), radial-gradient(4px at 50% bottom, transparent 49%, black 51%);
        mask-size: 100% 100%, 8px 8px;
        mask-repeat: no-repeat, repeat-x;
        mask-position: top, bottom;
        padding-bottom: 20px;
    }
    
    @media print {
        @page {
            margin: 0;
            size: auto;
        }
        
        body {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        body * {
            visibility: hidden;
        }
        
        .fixed.inset-0.z-\[60\] {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            min-height: auto !important;
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
        }
        
        .fixed.inset-0.z-\[60\] .bg-white {
            visibility: visible !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            margin: 0 auto !important;
            padding: 10px !important;
            width: 80mm !important; /* Standar printer thermal */
            max-width: 80mm !important;
        }
        
        .fixed.inset-0.z-\[60\] .bg-white *, .fixed.inset-0.z-\[60\] .bg-white *::before, .fixed.inset-0.z-\[60\] .bg-white *::after {
            visibility: visible !important;
        }

        .print\:hidden {
            display: none !important;
        }
        
        .zigzag-bottom {
            mask-image: none;
            padding-bottom: 0px !important;
        }
    }
</style>
