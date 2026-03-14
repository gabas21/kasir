<div class="py-8 text-text-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="animate-fade-in-up">
                <h2 class="text-2xl font-bold">Manajemen User</h2>
                <p class="text-text-muted mt-1">Kelola akun kasir, admin, dan owner.</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari user..."
                           class="bg-white border border-border rounded-lg pl-10 pr-4 py-2.5 text-sm text-text-main
                                  placeholder:text-text-muted/50 focus:outline-none focus:ring-2 focus:ring-gold/50 focus:border-gold w-64">
                </div>
                {{-- Add Button --}}
                <button wire:click="create"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gold text-white rounded-lg font-semibold text-sm
                               hover:opacity-90 active:scale-95 transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah User
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('message'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-success/10 border border-success/20 text-success text-sm font-medium flex items-center gap-2 toast-enter">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('message') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-danger/10 border border-danger/20 text-danger text-sm font-medium flex items-center gap-2 toast-enter">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Users Table --}}
        <div class="bg-white border border-border rounded-lg shadow-sm overflow-hidden animate-slide-up">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-cream">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider border-b border-border">User</th>
                            <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider border-b border-border">Role</th>
                            <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider border-b border-border">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider border-b border-border text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider border-b border-border text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($users as $user)
                            <tr class="hover:bg-cream/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-sidebar flex items-center justify-center text-white font-bold border-2 border-gold/20 shadow-inner">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-text-main">{{ $user->name }}</div>
                                            <div class="text-xs text-text-muted">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                                 {{ $user->role === 'admin' ? 'bg-gold/10 text-gold border border-gold/20' : 'bg-sidebar/5 text-sidebar border border-sidebar/10' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-main font-medium">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-active/10 text-active border border-active/20">
                                        ● ACTIVE
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="edit({{ $user->id }})" 
                                                class="p-2 rounded-lg bg-cream text-gold hover:bg-gold hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <button wire:click="delete({{ $user->id }})"
                                                    wire:confirm="Yakin ingin menghapus user {{ $user->name }}?"
                                                    class="p-2 rounded-lg bg-danger/10 text-danger hover:bg-danger hover:text-white transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-muted">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p>Belum ada user terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-border">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ==========================================
         MODAL FORM
    ========================================== --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-sidebar/60 backdrop-blur-sm">
            <div class="bg-white border border-border rounded-lg shadow-2xl w-full max-w-md mx-4 animate-scale-in"
                 @click.outside="$wire.closeModal()">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-border flex justify-between items-center bg-cream">
                    <h3 class="text-xl font-bold text-text-main">
                        {{ $userId ? 'Edit User' : 'Tambah User Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-text-muted hover:text-danger p-1 rounded-lg hover:bg-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Form Body --}}
                <div class="p-6 space-y-5">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-semibold text-text-muted uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" wire:model="name" placeholder="Contoh: Budi Santoso"
                               class="w-full bg-white border border-border rounded-lg px-4 py-3 text-sm text-text-main focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                        @error('name') <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-text-muted uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" wire:model="email" placeholder="budi@elitecafe.id"
                               class="w-full bg-white border border-border rounded-lg px-4 py-3 text-sm text-text-main focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                        @error('email') <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-text-muted mb-1.5">
                            Password
                            @if($userId)
                                <span class="text-text-muted/50 font-normal">(kosongkan jika tidak ingin mengubah)</span>
                            @endif
                        </label>
                        <input type="password" wire:model="password" placeholder="Minimal 6 karakter"
                               class="w-full bg-white border border-border rounded-lg px-4 py-3 text-sm text-text-main focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                        @error('password') <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-semibold text-text-muted uppercase tracking-wider mb-2">Role Akses</label>
                        <select wire:model="role" class="w-full bg-white border border-border rounded-lg px-4 py-3 text-sm text-text-main focus:ring-2 focus:ring-gold/50 focus:border-gold outline-none transition-all">
                            <option value="cashier">Cashier (Akses POS Only)</option>
                            <option value="admin">Admin (Akses Dashboard Full)</option>
                        </select>
                        @error('role') <p class="text-xs text-danger mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-border flex justify-end gap-3 bg-cream">
                    <button wire:click="closeModal" 
                            class="px-6 py-2.5 bg-white text-text-muted rounded-lg font-bold text-sm border border-border hover:bg-white transition-all">
                        BATAL
                    </button>
                    <button wire:click="store" 
                            class="px-8 py-2.5 bg-gold text-white rounded-lg font-bold text-sm hover:opacity-90 transition-all shadow-sm">
                        <span wire:loading.remove wire:target="store">{{ $userId ? 'SIMPAN PERUBAHAN' : 'TAMBAH USER' }}</span>
                        <span wire:loading wire:target="store" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
