<div class="h-full w-full flex flex-col items-center justify-center py-6 px-4 sm:px-6 lg:px-8 bg-surface pattern-bg relative overflow-y-auto">
    
    {{-- Background Decorators --}}
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-gold/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-500/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md space-y-8 bg-surface-2 p-8 rounded-2xl shadow-2xl border border-border z-10 my-auto">
        
        {{-- Header App --}}
        <div class="text-center">
            <div class="mx-auto h-20 w-20 rounded-2xl flex items-center justify-center mb-4 overflow-hidden">
                <img src="/images/gemini-svg.svg" alt="Elite Cafe Logo" class="w-full h-full object-cover">
            </div>
            <h2 class="text-3xl font-extrabold text-text-main tracking-tight">
                Elite <span class="{{ $loginMode === 'kasir' ? 'text-gold' : 'text-blue-400' }} transition-colors duration-300">Cafe</span>
            </h2>
            <p class="mt-2 text-sm text-text-muted">
                {{ $loginMode === 'kasir' ? 'Masuk untuk melayani pelanggan dan cetak struk.' : 'Masuk untuk mengelola stok, laporan, dan promo.' }}
            </p>
        </div>

        {{-- Tab Selector --}}
        <div class="flex bg-surface-3 rounded-xl p-1 border border-border">
            <button wire:click="setMode('kasir')" type="button"
                    class="flex-1 py-3 px-4 rounded-lg text-sm font-bold tracking-wide flex items-center justify-center gap-2 transition-all duration-200
                    {{ $loginMode === 'kasir' 
                        ? 'bg-gold text-white shadow-lg shadow-gold/20' 
                        : 'text-text-muted hover:text-text-main hover:bg-surface-2' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Kasir
            </button>
            <button wire:click="setMode('admin')" type="button"
                    class="flex-1 py-3 px-4 rounded-lg text-sm font-bold tracking-wide flex items-center justify-center gap-2 transition-all duration-200
                    {{ $loginMode === 'admin' 
                        ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/20' 
                        : 'text-text-muted hover:text-text-main hover:bg-surface-2' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Admin / Owner
            </button>
        </div>

        {{-- Form Login --}}
        <form wire:submit="login" class="space-y-6">
            @if (session()->has('error'))
                <div class="rounded-xl bg-danger-light p-4 border border-danger/20">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-danger" viewBox="0 0 20 20" fill="currentColor">
                               <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-danger">{{ session('error') }}</h3>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-xl -space-y-px bg-surface-3 border border-border overflow-hidden transition-all duration-300
                {{ $loginMode === 'admin' ? 'ring-1 ring-blue-500/20' : '' }}">
                <div class="relative">
                    <label for="email" class="sr-only">Email address</label>
                    <input wire:model="email" id="email" type="email" autocomplete="email" required 
                           class="appearance-none rounded-none relative block w-full px-4 py-4 border-0 border-b border-border bg-transparent text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:border-transparent focus:z-10 text-base
                           {{ $loginMode === 'kasir' ? 'focus:ring-gold' : 'focus:ring-blue-500' }}" 
                           placeholder="{{ $loginMode === 'kasir' ? 'Email kasir' : 'Email admin / owner' }}">
                    @error('email') <span class="absolute right-4 top-4 text-xs text-danger font-semibold">{{ $message }}</span> @enderror
                </div>
                
                <div class="relative">
                    <label for="password" class="sr-only">Password</label>
                    <input wire:model="password" id="password" type="password" autocomplete="current-password" required 
                           class="appearance-none rounded-none relative block w-full px-4 py-4 border-0 bg-transparent text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:border-transparent focus:z-10 text-base
                           {{ $loginMode === 'kasir' ? 'focus:ring-gold' : 'focus:ring-blue-500' }}" 
                           placeholder="Password">
                    @error('password') <span class="absolute right-4 top-4 text-xs text-danger font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-4 px-1">
                <div class="flex items-center">
                    <input wire:model="remember" id="remember" type="checkbox" 
                           class="h-5 w-5 rounded border-border bg-surface-3 focus:ring-offset-surface-2 transition
                           {{ $loginMode === 'kasir' ? 'text-gold focus:ring-gold' : 'text-blue-500 focus:ring-blue-500' }}">
                    <label for="remember" class="ml-2 block text-sm text-text-muted">
                        {{ $loginMode === 'kasir' ? 'Simpan sesi terminal' : 'Tetap masuk hari ini' }}
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-base font-bold rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-surface-2 transition-all duration-200 active:scale-[0.98] shadow-lg disabled:opacity-70 disabled:cursor-not-allowed
                        {{ $loginMode === 'kasir' 
                            ? 'bg-gold hover:bg-gold-hover focus:ring-gold' 
                            : 'bg-blue-500 hover:bg-blue-600 focus:ring-blue-500' }}"
                        wire:loading.attr="disabled">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg wire:loading.remove class="h-6 w-6 text-white/50 group-hover:text-white transition ease-in-out duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <svg wire:loading class="animate-spin h-6 w-6 text-white" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8C12 5.373 6.627 0 0 0v4a8 8 0 008 8z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove>
                        {{ $loginMode === 'kasir' ? 'Buka Terminal Kasir' : 'Masuk Dashboard' }}
                    </span>
                    <span wire:loading>Memverifikasi...</span>
                </button>
            </div>
        </form>

        {{-- Footer --}}
        <div class="pt-6 border-t border-border text-center">
            <p class="text-xs text-text-muted">&copy; {{ date('Y') }} Elite Cafe POS System</p>
        </div>
    </div>
    
    {{-- Decorative Background --}}
    <style>
        .pattern-bg {
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</div>
