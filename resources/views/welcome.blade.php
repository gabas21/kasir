<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Elite Cafe - Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        background: '#0F172A', // Slate 900
                        surface: '#1E293B',    // Slate 800
                        'surface-2': '#334155',// Slate 700
                        gold: '#F59E0B',       // Amber 500
                        'gold-hover': '#D97706',// Amber 600
                        'text-main': '#F8FAFC',// Slate 50
                        'text-muted': '#94A3B8'// Slate 400
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-text-main font-sans antialiased min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    
    <!-- Background Decorators -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-gold/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-gold/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="z-10 w-full max-w-4xl px-4 flex flex-col items-center animate-[fadeInUp_0.8s_ease-out]">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-surface-2 border border-surface/50 shadow-[0_0_30px_rgba(245,158,11,0.15)] mb-6">
                <!-- SVG Coffee/POS Icon -->
                <svg class="w-10 h-10 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-3 text-transparent bg-clip-text bg-gradient-to-r from-white to-text-muted">Elite <span class="text-gold">Cafe</span></h1>
            <p class="text-text-muted text-lg max-w-md mx-auto">Pilih portal masuk sesuai dengan peran dan tugas Anda hari ini.</p>
        </div>

        <!-- Portal Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl">
            
            <!-- Card: Kasir -->
            <a href="{{ route('pos.login') }}" class="group relative bg-surface border border-surface-2 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] hover:border-gold/30 flex flex-col items-center text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gold/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-16 h-16 bg-surface-2 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:text-gold transition-all duration-300 shadow-inner">
                    <svg class="w-8 h-8 text-text-muted group-hover:text-gold transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold mb-2 text-white">Portal Kasir</h2>
                <p class="text-text-muted text-sm mb-6">Mulai shift Anda, layani pelanggan, order menu, dan cetak struk pembayaran.</p>
                <div class="mt-auto px-6 py-2.5 rounded-full bg-surface-2 text-sm font-semibold text-white group-hover:bg-gold group-hover:text-white transition-colors duration-300 border border-transparent group-hover:border-gold-hover">
                    Masuk Mode POS
                </div>
            </a>

            <!-- Card: Admin -->
            <a href="{{ route('login') }}" class="group relative bg-surface border border-surface-2 rounded-2xl p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] hover:border-gold/30 flex flex-col items-center text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-16 h-16 bg-surface-2 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:text-blue-400 transition-all duration-300 shadow-inner">
                    <svg class="w-8 h-8 text-text-muted group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold mb-2 text-white">Portal Admin / Owner</h2>
                <p class="text-text-muted text-sm mb-6">Kelola stok produk, pantau pendapatan harian, atur diskon promo, dan manajemen pengguna.</p>
                <div class="mt-auto px-6 py-2.5 rounded-full bg-surface-2 text-sm font-semibold text-white group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300 border border-transparent group-hover:border-blue-600">
                    Masuk Manajemen
                </div>
            </a>

        </div>

        <footer class="mt-16 text-center text-xs text-text-muted/60">
            &copy; {{ date('Y') }} Elite Cafe POS System. Created securely for robust performance.
        </footer>
    </div>

    <!-- Simple Custom Animation for Intro -->
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
