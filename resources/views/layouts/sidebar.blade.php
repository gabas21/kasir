<aside class="fixed inset-y-0 left-0 w-56 bg-sidebar text-sidebar-text flex flex-col z-50">
    <!-- Logo -->
    <div class="px-6 py-8">
        <a wire:navigate href="{{ route('dashboard') }}" class="text-xl font-bold text-white tracking-tight">
            POS <span class="text-gold">CAFE</span>
        </a>
    </div>

    <!-- Navigation Items -->
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        @php
            $navItems = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'categories.index', 'label' => 'Kategori', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                ['route' => 'products.index', 'label' => 'Produk', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['route' => 'reports.index', 'label' => 'Laporan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['route' => 'promos.index', 'label' => 'Promo', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
            ];

            if(Auth::user() && Auth::user()->role === 'owner') {
                $navItems[] = ['route' => 'owner.dashboard', 'label' => 'Owner', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'];
                $navItems[] = ['route' => 'users.index', 'label' => 'Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];
            }
        @endphp

        @foreach($navItems as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <a wire:navigate href="{{ route($item['route']) }}" 
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors group {{ $isActive ? 'bg-gold text-white shadow-lg' : 'text-sidebar-text hover:bg-white/10' }}">
                <svg class="w-5 h-5 mr-3 {{ $isActive ? 'text-white' : 'text-sidebar-text group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach

        <!-- POS Link special styling -->
        <a wire:navigate href="{{ route('pos.index') }}" 
           class="flex items-center px-4 py-3 mt-4 text-sm font-bold rounded-lg border-2 border-gold/50 text-gold hover:bg-gold hover:text-white transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            BUKA POS
        </a>
    </nav>

    <!-- Bottom Section: User Profile -->
    <div class="p-4 border-t border-white/10">
        <div class="flex items-center px-2">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-gold/20 border border-gold/30 flex items-center justify-center text-gold font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-sidebar-text/70 truncate capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-center text-[10px] uppercase tracking-widest font-semibold">
            <a wire:navigate href="{{ route('profile.edit') }}" class="py-1 rounded bg-white/5 hover:bg-white/10 text-sidebar-text">Profil</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="w-full py-1 rounded bg-danger/10 hover:bg-danger/20 text-danger-400">Logout</button>
            </form>
        </div>
    </div>
</aside>
