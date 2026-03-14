<x-split-auth-layout>
    <div class="space-y-8">
        {{-- Header Form --}}
        <div>
            <h2 class="text-3xl font-bold text-text-main">Selamat Datang</h2>
            <p class="text-text-muted mt-2">Silakan login untuk mengakses sistem kasir.</p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Email Address --}}
            <div class="space-y-1">
                <x-input-label for="email" :value="__('Email')" class="text-xs uppercase tracking-wider font-bold text-text-muted" />
                <x-text-input id="email" class="block w-full py-3" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@elitecafe.id" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password --}}
            <div class="space-y-1">
                <div class="flex justify-between items-center">
                    <x-input-label for="password" :value="__('Password')" class="text-xs uppercase tracking-wider font-bold text-text-muted" />
                    @if (Route::has('password.request'))
                        <a class="text-xs font-semibold text-gold hover:text-gold-hover transition-colors" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <x-text-input id="password" class="block w-full py-3"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me --}}
            <div class="block">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" class="rounded border-border text-gold shadow-sm focus:ring-gold/30 cursor-pointer transition-all" name="remember">
                    <span class="ms-2 text-sm text-text-muted group-hover:text-text-main transition-colors">{{ __('Tetap masuk untuk hari ini') }}</span>
                </label>
            </div>

            <div class="pt-2">
                <x-primary-button class="py-4 shadow-lg shadow-gold/20">
                    <span class="text-base font-bold">{{ __('MASUK KE SISTEM') }}</span>
                </x-primary-button>
            </div>
        </form>

        {{-- Footer Form --}}
        <div class="pt-8 border-t border-border-soft flex justify-between items-center">
            <p class="text-xs text-text-muted">Butuh bantuan teknis? <a href="#" class="text-gold font-bold">Kontak IT</a></p>
            <div class="flex gap-2">
                <div class="w-2 h-2 rounded-full bg-gold"></div>
                <div class="w-2 h-2 rounded-full bg-sidebar"></div>
                <div class="w-2 h-2 rounded-full bg-cream border border-border"></div>
            </div>
        </div>
    </div>
</x-split-auth-layout>
