<div class="py-8 text-text-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="animate-fade-in-up">
                <h2 class="text-2xl font-bold">Manajemen Promo</h2>
                <p class="text-text-muted mt-1">Kelola kode promo diskon pelanggan.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari promo..."
                           class="bg-white border border-border rounded-lg pl-10 pr-4 py-2.5 text-sm text-text-main
                                  placeholder:text-text-muted/50 focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold w-64">
                </div>
                <button wire:click="create"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gold text-white rounded-lg font-semibold text-sm
                               hover:opacity-90 active:scale-95 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Promo
                </button>
            </div>
        </div>

        {{-- Flash --}}
        @if(session()->has('message'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-active/10 border border-active/20 text-active text-sm font-medium flex items-center gap-2 toast-enter">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('message') }}
            </div>
        @endif

        {{-- Promo Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($promos as $promo)
                <div class="bg-white border border-border rounded-lg p-5 shadow-sm
                            hover:border-gold/50 transition-all duration-300 relative overflow-hidden
                            {{ !$promo->is_active ? 'opacity-50' : '' }}"
                     style="animation-delay: {{ $loop->index * 0.08 }}s">
                    {{-- Decorative --}}
                    <div class="absolute -top-2 -right-2 w-20 h-20 bg-gradient-to-bl from-accent/10 to-transparent rounded-full"></div>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🎫</span>
                            <code class="text-lg font-bold text-gold tracking-wider">{{ $promo->code }}</code>
                        </div>
                         <button wire:click="toggleActive({{ $promo->id }})"
                                class="relative w-12 h-7 rounded-full transition-colors duration-200
                                       {{ $promo->is_active ? 'bg-active' : 'bg-surface' }}">
                            <span class="absolute top-1 transition-all duration-200 w-5 h-5 rounded-full bg-white shadow
                                         {{ $promo->is_active ? 'left-6' : 'left-1' }}"></span>
                        </button>
                    </div>

                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-3xl font-bold text-text-main">
                            @if($promo->type === 'percentage')
                                {{ intval($promo->value) }}%
                            @else
                                Rp {{ number_format($promo->value, 0, ',', '.') }}
                            @endif
                        </span>
                        <span class="text-text-muted text-sm">
                            {{ $promo->type === 'percentage' ? 'Diskon' : 'Potongan' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter
                                     {{ $promo->is_active ? 'bg-active/10 text-active border border-active/20' : 'bg-surface text-text-muted border border-border' }}">
                            {{ $promo->is_active ? '● AVAILABLE' : '● DISABLED' }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-border">
                        <button wire:click="edit({{ $promo->id }})"
                                class="flex-1 px-3 py-2 text-xs font-medium rounded-lg bg-gold/10 text-gold border border-gold/20
                                       hover:bg-gold hover:text-white text-center transition-all duration-200">
                            Edit
                        </button>
                        <button wire:click="delete({{ $promo->id }})"
                                wire:confirm="Yakin ingin menghapus promo {{ $promo->code }}?"
                                class="flex-1 px-3 py-2 text-xs font-medium rounded-lg bg-danger/10 text-danger border border-danger/20
                                       hover:bg-danger hover:text-white text-center transition-all duration-200">
                            Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <span class="text-4xl mb-3 block">🎫</span>
                    <p class="text-text-muted">Belum ada promo. Buat promo pertama!</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($promos->hasPages())
            <div class="mt-6">{{ $promos->links() }}</div>
        @endif
    </div>

    {{-- Modal Form --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-sidebar/60 backdrop-blur-sm">
            <div class="bg-white border border-border rounded-lg shadow-xl w-full max-w-md mx-4"
                 @click.outside="$wire.closeModal()">
                <div class="px-6 py-4 border-b border-border flex justify-between items-center">
                    <h3 class="text-lg font-bold">{{ $promoId ? 'Edit Promo' : 'Tambah Promo Baru' }}</h3>
                    <button wire:click="closeModal" class="text-text-muted hover:text-danger p-1 rounded-lg hover:bg-cream transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    {{-- Kode Promo --}}
                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-1.5">Kode Promo</label>
                        <input type="text" wire:model="code" placeholder="DISKON20" style="text-transform: uppercase"
                               class="w-full bg-white border border-border rounded-lg px-4 py-2.5 text-sm text-text-main
                                      placeholder:text-text-muted/50 focus:ring-2 focus:ring-gold/50 focus:border-gold">
                        @error('code') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipe --}}
                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-2">Tipe Diskon</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="$set('type', 'percentage')"
                                    class="py-3 rounded-lg border text-center transition-all text-sm font-semibold
                                           {{ $type === 'percentage' ? 'bg-gold/10 border-gold text-gold' : 'bg-white border-border text-text-muted' }}">
                                📊 Persentase (%)
                            </button>
                            <button type="button" wire:click="$set('type', 'fixed')"
                                    class="py-3 rounded-lg border text-center transition-all text-sm font-semibold
                                           {{ $type === 'fixed' ? 'bg-gold/10 border-gold text-gold' : 'bg-white border-border text-text-muted' }}">
                                💰 Nominal (Rp)
                            </button>
                        </div>
                    </div>

                    {{-- Nilai --}}
                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-1.5">
                            {{ $type === 'percentage' ? 'Persentase Diskon (%)' : 'Potongan Harga (Rp)' }}
                        </label>
                        <input type="number" wire:model="value" placeholder="{{ $type === 'percentage' ? '10' : '5000' }}" min="0"
                               class="w-full bg-white border border-border rounded-lg px-4 py-2.5 text-sm text-text-main
                                      placeholder:text-text-muted/50 focus:ring-2 focus:ring-gold/50 focus:border-gold">
                        @error('value') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-3">
                        <label class="text-xs font-semibold text-text-muted uppercase tracking-wider">Status:</label>
                        <button type="button" wire:click="$toggle('is_active')"
                                class="relative w-12 h-7 rounded-full transition-colors duration-200
                                       {{ $is_active ? 'bg-active' : 'bg-surface' }}">
                            <span class="absolute top-1 transition-all duration-200 w-5 h-5 rounded-full bg-white shadow
                                         {{ $is_active ? 'left-6' : 'left-1' }}"></span>
                        </button>
                        <span class="text-sm font-bold {{ $is_active ? 'text-active' : 'text-text-muted' }}">
                            {{ $is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-border flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-5 py-2.5 text-sm font-medium text-text-muted bg-white rounded-lg border border-border">Batal</button>
                    <button wire:click="store"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-gold rounded-lg
                                   hover:opacity-90 transition-all flex items-center gap-2">
                        <span wire:loading.remove wire:target="store">{{ $promoId ? 'Simpan' : 'Tambah Promo' }}</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
