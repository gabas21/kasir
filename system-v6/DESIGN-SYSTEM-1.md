# DESIGN-SYSTEM.md — Sumber Kebenaran Desain
> Dikunci dari referensi visual ServePoint POS by Lokomax Studio (14 halaman).
> Otoritas tertinggi — konflik dengan file lain → FILE INI YANG MENANG.
> Claude & Gemini WAJIB baca sebelum membuat atau mengubah apapun yang visual.

---

## 🎨 PALET WARNA

```
Sidebar BG    : #1B3A2D — Dark Forest Green
Gold          : #C8973A — Gold/Amber (SATU-SATUNYA accent warna)
Gold Hover    : #B8832A — Gold lebih gelap untuk hover
Gold Muted    : #C8973A1A — Gold transparan 10% (bg tag/badge)
Cream         : #F0EDE8 — Background halaman utama
Surface       : #E8E4DE — Background panel, sub-panel, card dalam
Panel         : #FFFFFF — Area konten bersih
Text Primary  : #1B2E1F — Hampir hitam warm
Text Muted    : #6B7B6E — Abu kehijauan
Text Sidebar  : #E8F0EA — Putih kehijauan (teks di sidebar)
Text Gold     : #C8973A — Harga, highlight penting
Border        : #D4CFC8 — Pemisah halaman
Border Light  : #E8E4DE — Pemisah dalam panel
Active Green  : #16A34A — Status aktif, online
Danger        : #DC2626 — Hapus, error, cancelled
Warning       : #D97706 — Pending, perhatian
```

**tailwind.config.js — WAJIB DIISI SEBELUM CODING:**
```js
theme: {
  extend: {
    colors: {
      'sidebar':       '#1B3A2D',
      'gold':          '#C8973A',
      'gold-hover':    '#B8832A',
      'gold-muted':    'rgba(200,151,58,0.1)',
      'cream':         '#F0EDE8',
      'surface':       '#E8E4DE',
      'panel':         '#FFFFFF',
      'text-main':     '#1B2E1F',
      'text-muted':    '#6B7B6E',
      'text-gold':     '#C8973A',
      'sidebar-text':  '#E8F0EA',
      'border-soft':   '#D4CFC8',
      'border-light':  '#E8E4DE',
      'active':        '#16A34A',
      'danger':        '#DC2626',
      'warning':       '#D97706',
    },
    fontFamily: {
      sans: ['Inter', 'system-ui', 'sans-serif'],
    }
  }
}
```

**Import font di resources/css/app.css:**
```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
```

---

## 🔤 TIPOGRAFI

```
Judul halaman  : text-2xl font-bold text-text-main
Heading card   : text-base font-semibold text-text-main
Sub heading    : text-sm font-semibold text-text-main
Body           : text-sm font-normal text-text-main
Muted          : text-sm text-text-muted
Harga/angka    : text-sm font-semibold text-gold (atau text-text-main untuk total)
Label form     : text-sm font-medium text-text-main
Nav item       : text-sm font-medium text-sidebar-text/70
Nav active     : text-sm font-semibold text-white
Breadcrumb     : text-xs text-text-muted
Caption        : text-xs text-text-muted
Badge text     : text-xs font-medium
```

---

## 📐 SPACING & LAYOUT

```
Sidebar width   : w-56 (224px) — fixed, tidak collapse di desktop
Border radius   : rounded-2xl → card besar, modal
                  rounded-xl  → card normal, panel
                  rounded-lg  → input, button, badge item
                  rounded-full → avatar, dot status, pill badge
Shadow          : shadow-sm  → card, panel
                  shadow-md  → dropdown, popover
                  shadow-lg  → modal
Padding sidebar : px-3 py-5
Padding konten  : p-6 (desktop) / p-4 (mobile)
Padding card    : p-5
Padding panel   : p-4
Gap grid        : gap-4 untuk card grid
Gap nav         : space-y-0.5
Gap form fields : space-y-4
Gap section     : space-y-6
```

---

## 🏗️ LAYOUT UTAMA

```html
{{-- resources/views/layouts/app.blade.php --}}
<div class="flex h-screen bg-cream font-sans overflow-hidden">

  {{-- SIDEBAR --}}
  <aside class="w-56 bg-sidebar flex flex-col flex-shrink-0 h-full overflow-y-auto">

    {{-- Logo --}}
    <div class="px-4 py-5 flex items-center gap-2.5">
      <div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center flex-shrink-0">
        <span class="text-white text-xs font-bold">SP</span>
      </div>
      <span class="text-sidebar-text font-semibold text-sm">ServePoint</span>
    </div>

    {{-- Navigasi utama --}}
    <nav class="flex-1 px-3 space-y-0.5">

      {{-- Nav item NORMAL --}}
      <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg
               text-sidebar-text/60 text-sm font-medium
               hover:bg-white/10 hover:text-sidebar-text
               transition-all duration-150 group">
        <x-icon name="dashboard" class="w-4 h-4 flex-shrink-0"/>
        <span>Dashboard</span>
      </a>

      {{-- Nav item ACTIVE --}}
      <a href="{{ route('pos') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg
               bg-gold text-white text-sm font-semibold
               transition-all duration-150">
        <x-icon name="food" class="w-4 h-4 flex-shrink-0"/>
        <span>Food & Drinks</span>
      </a>

      {{-- Divider + grup label --}}
      <div class="pt-4 pb-1">
        <p class="px-3 text-xs font-medium text-sidebar-text/30 uppercase tracking-wider">
          Others
        </p>
      </div>

      {{-- Nav dengan badge notif --}}
      <a href="{{ route('notifications') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg
               text-sidebar-text/60 text-sm font-medium
               hover:bg-white/10 hover:text-sidebar-text
               transition-all duration-150">
        <x-icon name="bell" class="w-4 h-4 flex-shrink-0"/>
        <span class="flex-1">Notifications</span>
        @if($notifCount > 0)
          <span class="w-5 h-5 bg-gold rounded-full text-white text-xs
                       flex items-center justify-center font-semibold">
            {{ $notifCount }}
          </span>
        @endif
      </a>

    </nav>

    {{-- User info bawah --}}
    <div class="px-4 py-4 mt-auto">
      <div class="flex items-center gap-3 mb-2">
        <img src="{{ auth()->user()->avatar_url ?? asset('img/avatar-default.png') }}"
          class="w-9 h-9 rounded-full object-cover ring-2 ring-white/20 flex-shrink-0">
        <div class="flex-1 min-w-0">
          <p class="text-sidebar-text text-xs font-semibold truncate">
            {{ auth()->user()->name }}
          </p>
          <p class="text-sidebar-text/40 text-xs truncate">
            {{ auth()->user()->role }} · {{ $shiftLabel ?? '08:00 - 16:00' }}
          </p>
        </div>
      </div>
      <a href="{{ route('profile') }}"
        class="block w-full text-center text-xs text-sidebar-text/50
               hover:text-sidebar-text border border-white/10 hover:border-white/20
               rounded-lg py-1.5 transition-all duration-150">
        Open profile
      </a>
      <p class="text-center text-sidebar-text/20 text-xs mt-3">
        © 2025 SmartPOS Setup
      </p>
    </div>

  </aside>

  {{-- KONTEN UTAMA --}}
  <main class="flex-1 flex flex-col overflow-hidden">

    {{-- Top bar --}}
    <div class="bg-cream px-6 py-3.5 border-b border-border-soft
                flex items-center justify-between flex-shrink-0">
      <div class="flex items-center gap-2 text-xs text-text-muted">
        @if(isset($breadcrumbParent))
          <a href="#" class="hover:text-text-main transition">{{ $breadcrumbParent }}</a>
          <span class="text-border-soft">›</span>
        @endif
        <span class="text-text-main font-medium">{{ $breadcrumbCurrent ?? 'Dashboard' }}</span>
      </div>
      <div class="flex items-center gap-3">
        {{-- Search global --}}
        @if(isset($showSearch) && $showSearch)
          <div class="flex items-center gap-2 bg-panel border border-border-soft
                      rounded-lg px-3 py-1.5 text-sm">
            <svg class="w-4 h-4 text-text-muted">...</svg>
            <input placeholder="Search ..." class="bg-transparent text-sm outline-none
                   text-text-main placeholder:text-text-muted w-32">
          </div>
        @endif
        {{-- Notif bell --}}
        <button class="w-8 h-8 rounded-lg hover:bg-surface flex items-center
                       justify-center text-text-muted transition relative">
          <svg class="w-4 h-4">...</svg>
          <span class="absolute top-1 right-1 w-2 h-2 bg-gold rounded-full"></span>
        </button>
        {{-- More --}}
        <button class="w-8 h-8 rounded-lg hover:bg-surface flex items-center
                       justify-center text-text-muted transition">
          ···
        </button>
      </div>
    </div>

    {{-- Page content --}}
    <div class="flex-1 overflow-y-auto p-6">
      {{ $slot }}
    </div>

  </main>
</div>
```

---

## 🔐 HALAMAN LOGIN (Onboarding)

```html
{{-- Split layout: ilustrasi kiri + form kanan --}}
<div class="min-h-screen bg-cream flex items-center justify-center p-4">
  <div class="w-full max-w-4xl bg-panel rounded-2xl shadow-lg overflow-hidden flex">

    {{-- Kiri: Ilustrasi --}}
    <div class="flex-1 bg-surface flex flex-col items-center justify-center p-10">
      <img src="{{ asset('img/onboarding.png') }}" class="w-64 mb-6">
      <h2 class="text-lg font-bold text-text-main text-center">
        Manage sales, inventory<br>and other transactions
      </h2>
      {{-- Dot pagination --}}
      <div class="flex gap-2 mt-6">
        <span class="w-6 h-2 bg-gold rounded-full"></span>
        <span class="w-2 h-2 bg-border-soft rounded-full"></span>
        <span class="w-2 h-2 bg-border-soft rounded-full"></span>
      </div>
    </div>

    {{-- Kanan: Form login --}}
    <div class="w-96 p-10 flex flex-col justify-center">
      <h1 class="text-2xl font-bold text-text-main mb-1">Welcome Back!</h1>
      <p class="text-sm text-text-muted mb-8">Please sign in to continue</p>

      <form wire:submit="login" class="space-y-4">
        <input type="text" wire:model.lazy="salesId"
          placeholder="Sales ID number"
          class="w-full bg-surface border border-border-soft rounded-lg px-4 py-3
                 text-sm text-text-main placeholder:text-text-muted
                 focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">

        <div class="relative">
          <input type="password" wire:model.lazy="password"
            placeholder="Password"
            class="w-full bg-surface border border-border-soft rounded-lg px-4 py-3
                   text-sm text-text-main placeholder:text-text-muted
                   focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
          <button type="button" class="absolute right-3 top-3 text-text-muted hover:text-text-main">
            👁
          </button>
        </div>

        <button type="submit"
          class="w-full bg-gold hover:bg-gold-hover text-white font-semibold
                 py-3 rounded-lg transition-all duration-150">
          Sign in
        </button>
      </form>

      <div class="flex items-center gap-3 my-5">
        <div class="flex-1 h-px bg-border-soft"></div>
        <span class="text-xs text-text-muted">or</span>
        <div class="flex-1 h-px bg-border-soft"></div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <button class="flex items-center justify-center gap-2 border border-border-soft
                       rounded-lg py-2.5 text-sm text-text-muted hover:bg-surface transition">
          <img src="{{ asset('img/facebook.svg') }}" class="w-4 h-4">
          Add Facebook
        </button>
        <button class="flex items-center justify-center gap-2 border border-border-soft
                       rounded-lg py-2.5 text-sm text-text-muted hover:bg-surface transition">
          <img src="{{ asset('img/google.svg') }}" class="w-4 h-4">
          Add Google
        </button>
      </div>

      <div class="text-center mt-6 space-y-2">
        <a href="#" class="block text-sm text-gold hover:underline">Forgot password?</a>
        <p class="text-xs text-text-muted">
          Don't have an account?
          <a href="#" class="text-gold hover:underline">Go to Registration</a>
        </p>
      </div>
    </div>

  </div>
</div>
```

---

## 📊 HALAMAN DASHBOARD

```html
{{-- Filter periode di atas --}}
<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold text-text-main">Dashboard</h1>
  <div class="flex items-center gap-1 bg-panel border border-border-soft rounded-lg p-1">
    @foreach(['Yesterday', 'Today', 'Week', 'Month', 'Year'] as $period)
      <button class="px-3 py-1.5 rounded-md text-xs font-medium transition
                     {{ $activePeriod === $period
                        ? 'bg-gold text-white'
                        : 'text-text-muted hover:text-text-main hover:bg-surface' }}">
        {{ $period }}
      </button>
    @endforeach
  </div>
</div>

{{-- Grid utama --}}
<div class="grid grid-cols-3 gap-4">

  {{-- Card grafik daily sales --}}
  <div class="col-span-1 bg-panel rounded-xl border border-border-soft p-5 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-sm font-semibold text-text-main">Daily sales</h3>
    </div>
    <div class="h-32">
      {{-- Chart.js line chart --}}
    </div>
  </div>

  {{-- Card donut total revenue --}}
  <div class="col-span-1 bg-panel rounded-xl border border-border-soft p-5 shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-sm font-semibold text-text-main">Total Revenue</h3>
      <select class="text-xs text-text-muted border border-border-soft rounded-md px-2 py-1">
        <option>Today</option>
      </select>
    </div>
    <div class="flex items-center justify-center h-32 relative">
      {{-- Donut chart --}}
      <span class="absolute text-xl font-bold text-text-main">Rp 8.9jt</span>
    </div>
    {{-- Legend --}}
    <div class="flex items-center justify-center gap-4 mt-3 text-xs text-text-muted">
      <span class="flex items-center gap-1">
        <span class="w-2 h-2 rounded-full bg-sidebar inline-block"></span> Dine-in
      </span>
      <span class="flex items-center gap-1">
        <span class="w-2 h-2 rounded-full bg-gold inline-block"></span> Takeaway
      </span>
    </div>
  </div>

  {{-- Kolom kanan: stat cards --}}
  <div class="col-span-1 space-y-4">
    <div class="bg-panel rounded-xl border border-border-soft p-4 shadow-sm">
      <div class="flex items-center gap-2 mb-1">
        <span class="w-3 h-3 bg-danger rounded-sm"></span>
        <span class="text-xs text-text-muted font-medium">Total Order</span>
        <span class="text-xs text-danger ml-auto">-2.34%</span>
      </div>
      <p class="text-2xl font-bold text-text-main">278</p>
      <div class="mt-2 h-1 bg-surface rounded-full">
        <div class="h-1 bg-danger rounded-full w-3/4"></div>
      </div>
    </div>
    <div class="bg-panel rounded-xl border border-border-soft p-4 shadow-sm">
      <div class="flex items-center gap-2 mb-1">
        <span class="w-3 h-3 bg-gold rounded-sm"></span>
        <span class="text-xs text-text-muted font-medium">New Customers</span>
        <span class="text-xs text-active ml-auto">+23.65%</span>
      </div>
      <p class="text-2xl font-bold text-text-main">58</p>
      <div class="mt-2 h-1 bg-surface rounded-full">
        <div class="h-1 bg-gold rounded-full w-1/2"></div>
      </div>
    </div>
  </div>

</div>

{{-- Baris bawah: Best Employees + Trending Dishes --}}
<div class="grid grid-cols-2 gap-4 mt-4">

  {{-- Best Employees --}}
  <div class="bg-panel rounded-xl border border-border-soft shadow-sm overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-border-light">
      <h3 class="text-sm font-semibold text-text-main">Best Employees</h3>
      <select class="text-xs text-text-muted border border-border-soft rounded-md px-2 py-1">
        <option>Today</option>
      </select>
    </div>
    <div class="divide-y divide-border-light">
      @foreach($topEmployees as $emp)
        <div class="px-5 py-3 flex items-center gap-3">
          <img src="{{ $emp->avatar }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-text-main truncate">{{ $emp->name }}</p>
            <p class="text-xs text-text-muted">{{ $emp->role }}</p>
          </div>
          <span class="text-sm font-semibold text-text-main">
            Rp {{ number_format($emp->sales, 0, ',', '.') }}
          </span>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Trending Dishes --}}
  <div class="bg-panel rounded-xl border border-border-soft shadow-sm overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between border-b border-border-light">
      <h3 class="text-sm font-semibold text-text-main">Trending Dishes</h3>
      <select class="text-xs text-text-muted border border-border-soft rounded-md px-2 py-1">
        <option>Today</option>
      </select>
    </div>
    <div class="divide-y divide-border-light">
      @foreach($trendingItems as $item)
        <div class="px-5 py-3 flex items-center gap-3">
          <img src="{{ $item->image }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
          <div class="flex-1 min-w-0">
            <span class="inline-block px-1.5 py-0.5 rounded text-xs font-medium mb-0.5
                         {{ $item->type === 'Food' ? 'bg-gold-muted text-gold' : 'bg-active/10 text-active' }}">
              {{ $item->type }}
            </span>
            <p class="text-sm font-medium text-text-main truncate">{{ $item->name }}</p>
          </div>
          <span class="text-sm font-semibold text-text-main">{{ $item->orders }}</span>
        </div>
      @endforeach
    </div>
  </div>

</div>
```

---

## 🛒 HALAMAN FOOD & DRINKS (Kategori)

```html
{{-- Grid kategori besar --}}
<div class="grid grid-cols-3 gap-4">
  @foreach($categories as $cat)
    <a href="{{ route('category.show', $cat) }}"
      class="bg-panel rounded-2xl border border-border-soft p-6
             flex flex-col items-center justify-center aspect-square
             hover:border-gold hover:shadow-md transition-all duration-200
             {{ $cat->isActive ? 'bg-gold border-gold' : '' }}">
      <img src="{{ $cat->image }}" class="w-20 h-20 object-contain mb-3">
      <p class="text-sm font-semibold {{ $cat->isActive ? 'text-white' : 'text-text-main' }}">
        {{ $cat->name }}
      </p>
    </a>
  @endforeach
</div>
```

---

## 🍔 HALAMAN CATEGORY MENU (3 Panel)

```html
{{-- 3 panel: list menu (kiri) + detail item (kanan) --}}
<div class="flex gap-4 h-full -m-6 overflow-hidden">

  {{-- Panel kiri: grid menu item --}}
  <div class="flex-1 p-6 overflow-y-auto">
    <div class="grid grid-cols-3 gap-3">
      @foreach($items as $item)
        <button wire:click="selectItem({{ $item->id }})"
          class="bg-panel rounded-xl border p-3 text-left
                 transition-all duration-150
                 {{ $selectedItem?->id === $item->id
                    ? 'border-gold bg-gold text-white shadow-md'
                    : 'border-border-soft hover:border-gold hover:shadow-sm' }}">
          <img src="{{ $item->image }}"
            class="w-full aspect-square object-contain rounded-lg mb-2">
          <p class="text-xs font-semibold {{ $selectedItem?->id === $item->id ? 'text-white' : 'text-text-main' }} truncate">
            {{ $item->name }}
          </p>
          <p class="text-xs {{ $selectedItem?->id === $item->id ? 'text-white/80' : 'text-text-muted' }}">
            {{ $item->weight }}g
          </p>
          <p class="text-xs font-semibold mt-1 {{ $selectedItem?->id === $item->id ? 'text-white' : 'text-gold' }}">
            Rp {{ number_format($item->price, 0, ',', '.') }}
          </p>
        </button>
      @endforeach
    </div>
  </div>

  {{-- Panel kanan: detail + tambah ke order --}}
  <div class="w-72 bg-panel border-l border-border-soft flex flex-col p-5">
    @if($selectedItem)
      <img src="{{ $selectedItem->image }}" class="w-32 h-32 object-contain mx-auto mb-4">
      <h3 class="text-base font-bold text-text-main text-center">{{ $selectedItem->name }}</h3>
      <p class="text-xs text-text-muted text-center mb-1">{{ $selectedItem->weight }}g</p>
      <p class="text-lg font-bold text-gold text-center mb-6">
        Rp {{ number_format($selectedItem->price, 0, ',', '.') }}
      </p>

      {{-- Item di keranjang dengan quantity --}}
      <div class="flex-1 space-y-3 overflow-y-auto">
        @foreach($cartItems as $cartItem)
          <div class="flex items-center gap-3">
            <img src="{{ $cartItem->image }}" class="w-8 h-8 rounded-lg object-cover">
            <span class="flex-1 text-sm text-text-main truncate">{{ $cartItem->name }}</span>
            <div class="flex items-center gap-1">
              <button wire:click="decrement({{ $cartItem->id }})"
                class="w-5 h-5 rounded-full border border-border-soft text-text-muted
                       text-xs hover:border-gold hover:text-gold transition flex items-center justify-center">
                −
              </button>
              <span class="text-xs font-medium w-5 text-center">{{ $cartItem->qty }}x</span>
              <button wire:click="increment({{ $cartItem->id }})"
                class="w-5 h-5 rounded-full border border-border-soft text-text-muted
                       text-xs hover:border-gold hover:text-gold transition flex items-center justify-center">
                +
              </button>
            </div>
          </div>
        @endforeach
      </div>

      <button wire:click="addToOrder"
        class="mt-4 w-full bg-gold hover:bg-gold-hover text-white font-semibold
               py-3 rounded-xl transition-all duration-150">
        Add to Order
      </button>
    @else
      {{-- Empty state --}}
      <div class="flex-1 flex items-center justify-center text-center">
        <div>
          <p class="text-text-muted text-sm">Pilih item untuk melihat detail</p>
        </div>
      </div>
    @endif
  </div>

</div>
```

---

## 🧾 HALAMAN BILLS (2 Panel)

```html
<div class="flex gap-4 h-full -m-6 overflow-hidden">

  {{-- Panel kiri: list orders --}}
  <div class="w-72 border-r border-border-soft flex flex-col">
    <div class="px-5 py-4 border-b border-border-soft flex items-center justify-between">
      <h2 class="text-base font-bold text-text-main">Bills</h2>
      <button class="w-7 h-7 bg-gold rounded-lg text-white text-sm flex items-center justify-center">
        +
      </button>
    </div>

    <div class="flex items-center gap-2 px-4 py-3 border-b border-border-soft">
      <select class="text-xs text-text-muted border border-border-soft rounded-md px-2 py-1.5 flex-1">
        <option>All orders</option>
      </select>
      <select class="text-xs text-text-muted border border-border-soft rounded-md px-2 py-1.5">
        <option>March 23</option>
      </select>
    </div>

    <div class="flex-1 overflow-y-auto divide-y divide-border-light">
      @foreach($orders as $order)
        <button wire:click="selectOrder({{ $order->id }})"
          class="w-full px-4 py-3.5 text-left hover:bg-surface transition
                 {{ $selectedOrder?->id === $order->id ? 'bg-surface' : '' }}">
          <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2">
              <span class="text-sm font-semibold text-text-main">Order #{{ $order->number }}</span>
              <span class="flex items-center gap-1 text-xs
                           {{ $order->status === 'Active' ? 'text-active' : ($order->status === 'Paid' ? 'text-gold' : 'text-danger') }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                {{ $order->status }}
              </span>
            </div>
            <span class="text-sm font-semibold text-text-main">
              Rp {{ number_format($order->total, 0, ',', '.') }}
            </span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-xs text-text-muted">
              Table {{ $order->table }} · {{ $order->guests }} guests
            </span>
            <span class="text-xs text-text-muted">{{ $order->time }}</span>
          </div>
        </button>
      @endforeach
    </div>

    <div class="p-3 border-t border-border-soft">
      <div class="flex items-center gap-2 bg-surface rounded-lg px-3 py-2">
        <svg class="w-4 h-4 text-text-muted">...</svg>
        <input placeholder="Search for order ..."
          class="bg-transparent text-xs text-text-main placeholder:text-text-muted outline-none flex-1">
      </div>
    </div>
  </div>

  {{-- Panel kanan: detail order --}}
  <div class="flex-1 p-6 flex flex-col overflow-y-auto">
    @if($selectedOrder)
      <div class="flex items-center justify-between mb-5">
        <div>
          <p class="text-xs text-text-muted mb-1">Payment history ∨</p>
          <h2 class="text-xl font-bold text-text-main">Order #{{ $selectedOrder->number }}</h2>
        </div>
        <span class="px-3 py-1 bg-gold text-white text-xs font-semibold rounded-lg">
          {{ $selectedOrder->status }}
        </span>
      </div>

      {{-- Detail info --}}
      <div class="bg-surface rounded-xl p-4 mb-4">
        <h3 class="text-sm font-semibold text-text-main mb-3">Details</h3>
        <div class="grid grid-cols-4 gap-4 text-xs">
          <div>
            <p class="text-text-muted mb-1">Table</p>
            <p class="font-semibold text-text-main">{{ $selectedOrder->table }}</p>
          </div>
          <div>
            <p class="text-text-muted mb-1">Guests</p>
            <p class="font-semibold text-text-main">{{ $selectedOrder->guests }}</p>
          </div>
          <div>
            <p class="text-text-muted mb-1">Customers</p>
            <p class="font-semibold text-text-main">{{ $selectedOrder->customer }}</p>
          </div>
          <div>
            <p class="text-text-muted mb-1">Payment</p>
            <p class="font-semibold text-warning">{{ $selectedOrder->payment_status }}</p>
          </div>
        </div>
      </div>

      {{-- Order items --}}
      <div class="flex-1">
        <div class="flex items-center justify-between text-xs text-text-muted mb-3 px-1">
          <span>Items</span>
          <span>Price</span>
        </div>
        <div class="space-y-3">
          @foreach($selectedOrder->items as $item)
            <div class="flex items-center gap-3">
              <img src="{{ $item->image }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
              <span class="flex-1 text-sm text-text-main">
                {{ $item->name }}
                @if($item->qty > 1) ({{ $item->qty }}x) @endif
              </span>
              <span class="text-sm font-medium text-text-main">
                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
              </span>
            </div>
          @endforeach
        </div>
        <div class="border-t border-border-soft mt-4 pt-4 flex items-center justify-between">
          <span class="text-sm font-semibold text-text-main">Total</span>
          <span class="text-base font-bold text-text-main">
            Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}
          </span>
        </div>
      </div>

      <button wire:click="chargeCustomer"
        class="mt-4 w-full bg-gold hover:bg-gold-hover text-white font-semibold
               py-3.5 rounded-xl transition-all duration-150">
        Charge customer Rp {{ number_format($selectedOrder->total, 0, ',', '.') }}
      </button>
    @endif
  </div>

</div>
```

---

## ⚙️ HALAMAN SETTINGS (3 Panel)

```html
<div class="flex gap-0 h-full -m-6 overflow-hidden">

  {{-- Panel 1: Menu settings --}}
  <div class="w-56 border-r border-border-soft p-4">
    <h2 class="text-lg font-bold text-text-main mb-4">Settings</h2>
    <nav class="space-y-0.5">
      @foreach($settingsMenu as $menu)
        <a href="{{ route('settings.'.$menu['key']) }}"
          class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition
                 {{ $activeMenu === $menu['key']
                    ? 'font-semibold text-text-main'
                    : 'text-text-muted hover:text-text-main hover:bg-surface' }}">
          <x-icon :name="$menu['icon']" class="w-4 h-4 flex-shrink-0"/>
          {{ $menu['label'] }}
        </a>
      @endforeach
    </nav>
  </div>

  {{-- Panel 2: Konten settings --}}
  <div class="flex-1 p-6 overflow-y-auto">
    {{ $slot }}
  </div>

</div>
```

---

## 🧩 KOMPONEN UI STANDAR

### Button
```html
{{-- Primary Gold --}}
<button class="bg-gold hover:bg-gold-hover text-white px-5 py-2.5
               rounded-lg text-sm font-semibold transition-all duration-150
               disabled:opacity-50 disabled:cursor-not-allowed">
  Save Changes
</button>

{{-- Full width (untuk settings/form) --}}
<button class="w-full bg-gold hover:bg-gold-hover text-white font-semibold
               py-3 rounded-xl transition-all duration-150">
  Save Changes
</button>

{{-- Dengan wire:loading --}}
<button wire:click="save" wire:loading.attr="disabled"
  class="bg-gold hover:bg-gold-hover text-white px-5 py-2.5 rounded-lg
         text-sm font-semibold transition disabled:opacity-50">
  <span wire:loading.remove>Save Changes</span>
  <span wire:loading>Menyimpan...</span>
</button>
```

### Input
```html
<div class="space-y-1.5">
  <label class="block text-sm font-medium text-text-main">Label</label>
  <input type="text" wire:model.lazy="field"
    class="w-full bg-surface border border-border-soft rounded-lg px-4 py-2.5
           text-sm text-text-main placeholder:text-text-muted
           focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
  @error('field') <p class="text-xs text-danger">{{ $message }}</p> @enderror
</div>
```

### Toggle Switch
```html
<div class="flex items-center justify-between py-2">
  <span class="text-sm text-text-main">Label</span>
  <button wire:click="toggle"
    class="relative w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none
           {{ $enabled ? 'bg-gold' : 'bg-surface border border-border-soft' }}">
    <span class="inline-block w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200
                 {{ $enabled ? 'translate-x-6' : 'translate-x-1' }}">
    </span>
  </button>
</div>
```

### Badge Status
```html
{{-- Active --}}
<span class="inline-flex items-center gap-1 text-xs font-medium text-active">
  <span class="w-1.5 h-1.5 rounded-full bg-active"></span> Active
</span>

{{-- Paid --}}
<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gold-muted text-gold">
  Paid
</span>

{{-- Cancelled --}}
<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">
  Cancelled
</span>
```

### Modal Konfirmasi
```html
<div x-data="{ open: false }">
  <button @click="open = true" class="bg-gold text-white ...">Trigger</button>
  <div x-show="open" x-cloak x-transition.opacity
    class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click.self="open = false">
    <div x-transition.scale class="bg-panel rounded-2xl shadow-lg w-full max-w-sm">
      <div class="px-6 py-5 border-b border-border-soft">
        <h3 class="text-base font-semibold text-text-main">Judul Modal</h3>
      </div>
      <div class="p-6 text-sm text-text-muted">Deskripsi aksi yang akan dilakukan.</div>
      <div class="px-6 py-4 border-t border-border-soft flex justify-end gap-3">
        <button @click="open = false"
          class="px-4 py-2 border border-border-soft rounded-lg text-sm text-text-muted
                 hover:text-text-main transition">Batal</button>
        <button class="px-4 py-2 bg-gold hover:bg-gold-hover text-white rounded-lg
                       text-sm font-semibold transition">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>
```

### Empty State
```html
<div class="flex flex-col items-center justify-center py-16 text-center">
  <div class="w-14 h-14 bg-surface rounded-2xl flex items-center justify-center mb-4">
    <svg class="w-6 h-6 text-text-muted">...</svg>
  </div>
  <h3 class="text-sm font-semibold text-text-main mb-1">Belum ada data</h3>
  <p class="text-xs text-text-muted mb-4 max-w-xs">Deskripsi singkat kenapa kosong</p>
  <button class="bg-gold text-white px-4 py-2 rounded-lg text-sm font-semibold">
    Tambah Sekarang
  </button>
</div>
```

---

## 📱 RESPONSIVE

```
Desktop lg+  : Layout penuh, sidebar w-56, grid 3+ kolom
Tablet md    : Sidebar collapse ke icon (w-14), grid 2 kolom
Mobile sm-   : Sidebar overlay (hidden default, buka via hamburger)

Halaman Bills & Category Menu:
  Desktop : 2-3 panel side by side
  Mobile  : Panel kanan jadi bottom sheet / modal

Dashboard:
  Desktop : Grid 3 kolom
  Mobile  : Stack vertikal 1 kolom
```

---

## 🧩 REGISTRY KOMPONEN

### Blade Components
| Nama | File | Deskripsi | Props |
|------|------|-----------|-------|
| - | - | - | - |

### Livewire Components
| Nama Class | File View | Deskripsi | Properties |
|---|---|---|---|
| - | - | - | - |

---

## 📄 REGISTRY HALAMAN

| Route Name | URL | File Blade | Livewire? | Status |
|---|---|---|---|---|
| login | /login | auth/login | ✅ | ⏳ |
| dashboard | /dashboard | dashboard | ✅ | ⏳ |
| food.index | /food | food/index | ✅ | ⏳ |
| bills.index | /bills | bills/index | ✅ | ⏳ |
| settings.* | /settings/* | settings/* | ✅ | ⏳ |

---

## 📝 KEPUTUSAN DESAIN DIKUNCI

| Keputusan | Alasan |
|---|---|
| Sidebar selalu dark green #1B3A2D | Brand identity — tidak ada opsi light sidebar |
| Gold adalah SATU-SATUNYA accent | Agar tidak ramai, premium feel |
| Background cream #F0EDE8 bukan putih | Lebih hangat, tidak melelahkan mata |
| Panel dalam pakai #E8E4DE bukan putih | Hierarchy visual yang jelas |
| Semua transition pakai duration-150 | Cepat, tidak terasa lambat |
| Border radius minimum rounded-lg | Tidak terlalu kotak, tidak terlalu bulat |

---

## ⚠️ ATURAN WAJIB

1. Cek Registry sebelum buat komponen — jangan duplikasi
2. Update Registry setelah buat komponen baru
3. DILARANG hardcode hex — selalu pakai token
4. Semua interactive element WAJIB ada hover + transition
5. Semua halaman kosong WAJIB punya empty state yang didesain
6. File ini vs file lain konflik → FILE INI YANG MENANG
