<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.pos-layout')]
class Kasir extends Component
{
    // =========================================================================
    // STATE PROPERTIES
    // =========================================================================

    /** @var array<int, array> Keranjang belanja */
    public array $cart = [];

    public string $selectedCategory = 'all';

    // State Modal Pembayaran
    public bool $showPaymentModal = false;
    public bool $showReceiptModal = false;
    public string $paymentMethod = 'cash';

    /**
     * PERBAIKAN KRITIS: Gunakan string tanpa strict-type.
     * Livewire v3 mengirimkan empty string saat input number dikosongkan,
     * sehingga tidak bisa strict int. Konversi ke int di backend saat dibutuhkan.
     */
    public string $paid_amount = '';

    public string $notes = '';

    /**
     * PERBAIKAN KRITIS: Jangan simpan Model Eloquent sebagai Livewire property.
     * Ini adalah anti-pattern di Livewire v3 karena menyebabkan serialization error.
     * Simpan hanya ID-nya, lalu query ulang dari DB saat dibutuhkan.
     */
    public ?int $currentTransactionId = null;

    // State Promo
    public string $promoCode  = '';
    public int    $discountAmount = 0;
    public string $promoMessage  = '';
    public bool   $promoValid    = false;

    // =========================================================================
    // LIFECYCLE
    // =========================================================================

    public function mount(): void
    {
        if (!Auth::check()) {
            redirect()->route('pos.login');
        }
    }

    // =========================================================================
    // COMPUTED PROPERTIES
    // Menggunakan #[Computed] agar di-cache per-request dan tidak menyebabkan
    // extra DB query yang tidak perlu.
    // =========================================================================

    #[Computed]
    public function categories(): array
    {
        return Category::select('slug', 'name')->get()->pluck('name', 'slug')->toArray();
    }

    #[Computed]
    public function filteredProducts()
    {
        $query = Product::with('category')->where('is_available', true);

        if ($this->selectedCategory !== 'all') {
            $query->whereHas('category', fn($q) => $q->where('slug', $this->selectedCategory));
        }

        return $query->get();
    }

    #[Computed]
    public function subtotal(): int
    {
        return (int) array_sum(array_map(
            fn($item) => $item['price'] * $item['qty'],
            $this->cart
        ));
    }

    #[Computed]
    public function total(): int
    {
        return max(0, $this->subtotal - $this->discountAmount);
    }

    #[Computed]
    public function itemCount(): int
    {
        return (int) array_sum(array_column($this->cart, 'qty'));
    }

    /**
     * PERBAIKAN KRITIS: Tidak lagi akses $this->paid_amount secara langsung
     * untuk kalkulasi. Konversi ke int secara eksplisit untuk keamanan aritmatika.
     */
    #[Computed]
    public function changeAmount(): int
    {
        return max(0, (int)$this->paid_amount - $this->total);
    }

    #[Computed]
    public function isCartEmpty(): bool
    {
        return empty($this->cart);
    }

    /**
     * PERBAIKAN KRITIS: Akses transaksi melalui query berdasarkan ID,
     * bukan menyimpan model Eloquent langsung di state Livewire.
     */
    #[Computed]
    public function currentTransaction(): ?Transaction
    {
        if (!$this->currentTransactionId) return null;
        return Transaction::with('details.product', 'user')->find($this->currentTransactionId);
    }

    // =========================================================================
    // ACTIONS
    // =========================================================================

    public function openPaymentModal(): void
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang masih kosong!');
            return;
        }

        $this->showPaymentModal = true;
        $this->paymentMethod   = 'cash';
        $this->paid_amount     = '';
        $this->notes           = '';

        // Reset promo juga hanya jika belum divalidasi — jika sudah valid biarkan
        if (!$this->promoValid) {
            $this->promoCode    = '';
            $this->promoMessage = '';
        }
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal    = false;
        $this->currentTransactionId = null;
        $this->clearCart();
        unset($this->currentTransaction); // clear computed cache
    }

    public function addToCart(int $productId): void
    {
        $product = Product::with('category')->find($productId);

        if (!$product || !$product->is_available) return;

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'category' => $product->category->name ?? 'Lainnya',
                'qty'      => 1,
            ];
        }
    }

    public function increment(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        }
    }

    public function decrement(int $productId): void
    {
        if (!isset($this->cart[$productId])) return;

        if ($this->cart[$productId]['qty'] <= 1) {
            unset($this->cart[$productId]);
        } else {
            $this->cart[$productId]['qty']--;
        }
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function clearCart(): void
    {
        $this->cart          = [];
        $this->discountAmount = 0;
        $this->promoCode     = '';
        $this->promoMessage  = '';
        $this->promoValid    = false;
    }

    public function applyPromo(): void
    {
        if (empty($this->promoCode)) {
            $this->promoMessage = 'Masukkan kode promo.';
            $this->promoValid   = false;
            $this->discountAmount = 0;
            return;
        }

        $promo = Promo::where('code', strtoupper($this->promoCode))
            ->where('is_active', true)
            ->first();

        if (!$promo) {
            $this->promoMessage = 'Kode promo tidak valid atau sudah nonaktif.';
            $this->promoValid   = false;
            $this->discountAmount = 0;
            return;
        }

        $subtotal = $this->subtotal;

        if ($promo->type === 'percentage') {
            $this->discountAmount = (int) ($subtotal * ($promo->value / 100));
            $this->promoMessage   = 'Diskon ' . intval($promo->value) . '% diterapkan!';
            $this->promoValid     = true;
        } else {
            if ($promo->value > $subtotal) {
                $this->promoMessage   = 'Minimal belanja untuk promo ini adalah Rp ' . number_format($promo->value, 0, ',', '.');
                $this->promoValid     = false;
                $this->discountAmount = 0;
            } else {
                $this->discountAmount = (int) $promo->value;
                $this->promoMessage   = 'Potongan Rp ' . number_format($promo->value, 0, ',', '.') . ' diterapkan!';
                $this->promoValid     = true;
            }
        }
    }

    public function removePromo(): void
    {
        $this->promoCode     = '';
        $this->discountAmount = 0;
        $this->promoMessage  = '';
        $this->promoValid    = false;
    }

    public function checkout(): void
    {
        if (empty($this->cart)) {
            $this->closePaymentModal();
            session()->flash('error', 'Keranjang masih kosong!');
            return;
        }

        try {
            DB::beginTransaction();

            $actualSubtotal      = 0;
            $transactionDetails  = [];
            $productIds          = array_keys($this->cart);

            // Validasi produk dengan pessimistic locking (cegah race condition)
            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($this->cart as $productId => $item) {
                if (!isset($products[$productId])) {
                    throw new \Exception("Produk '{$item['name']}' tidak ditemukan.");
                }

                $product = $products[$productId];
                $qty     = (int) $item['qty'];

                if (!$product->is_available) {
                    throw new \Exception("Produk '{$product->name}' saat ini tidak tersedia.");
                }

                $subtotalItem     = $product->price * $qty;
                $actualSubtotal  += $subtotalItem;

                $transactionDetails[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'qty'          => $qty,
                    'price'        => $product->price,
                    'subtotal'     => $subtotalItem,
                ];
            }

            // Hitung ulang diskon di server (cegah client-side tampering)
            $actualDiscount = 0;
            if ($this->promoValid && !empty($this->promoCode)) {
                $promo = Promo::where('code', strtoupper($this->promoCode))
                    ->where('is_active', true)
                    ->first();

                if ($promo) {
                    $actualDiscount = $promo->type === 'percentage'
                        ? (int) ($actualSubtotal * ($promo->value / 100))
                        : (int) min($promo->value, $actualSubtotal);
                }
            }

            $actualTotal    = max(0, $actualSubtotal - $actualDiscount);
            $paidAmount     = (int) $this->paid_amount;

            // Validasi uang tunai cukup
            if ($this->paymentMethod === 'cash' && $paidAmount < $actualTotal) {
                DB::rollBack();
                session()->flash('payment_error', 'Uang yang dibayarkan kurang dari total tagihan.');
                return;
            }

            // Buat transaksi utama
            $transaction = Transaction::create([
                'user_id'        => Auth::id(),
                'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'total_price'    => $actualTotal,
                'status'         => 'paid',
                'payment_method' => $this->paymentMethod,
                'paid_amount'    => $this->paymentMethod === 'cash' ? $paidAmount : $actualTotal,
                'change_amount'  => $this->paymentMethod === 'cash' ? max(0, $paidAmount - $actualTotal) : 0,
                'notes'          => $this->notes,
                'promo_code'     => $this->promoValid ? strtoupper($this->promoCode) : null,
                'discount_amount'=> $actualDiscount,
            ]);

            // Simpan detail transaksi sekaligus (bulk insert)
            $now = now();
            foreach ($transactionDetails as &$detail) {
                $detail['transaction_id'] = $transaction->id;
                $detail['created_at']     = $now;
                $detail['updated_at']     = $now;
            }
            TransactionDetail::insert($transactionDetails);

            DB::commit();

            // Tampilkan struk & reset state
            $this->currentTransactionId = $transaction->id;
            unset($this->currentTransaction); // force computed re-fetch
            $this->closePaymentModal();
            $this->showReceiptModal = true;
            $this->clearCart();
            $this->dispatch('sale-completed');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->closePaymentModal();
            session()->flash('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.kasir');
    }
}
