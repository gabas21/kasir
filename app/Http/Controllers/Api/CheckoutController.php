<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Buat transaksi baru dari Android POS
     *
     * Request body:
     * {
     *   "items": [
     *     {"product_id": 1, "qty": 2},
     *     {"product_id": 5, "qty": 1}
     *   ],
     *   "payment_method": "cash",  // cash | qris | transfer
     *   "paid_amount": 50000,
     *   "notes": "Meja 3"
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.qty'           => 'required|integer|min:1',
            'payment_method'        => 'required|in:cash,qris,transfer',
            'paid_amount'           => 'required_if:payment_method,cash|numeric|min:0',
            'notes'                 => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request) {
            // Hitung total
            $totalPrice = 0;
            $cartItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->is_available) {
                    return response()->json([
                        'status'  => false,
                        'message' => "Produk '{$product->name}' sedang tidak tersedia.",
                    ], 422);
                }

                $subtotal = $product->price * $item['qty'];
                $totalPrice += $subtotal;

                $cartItems[] = [
                    'product'  => $product,
                    'qty'      => $item['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            // Validasi uang bayar (cash)
            $paidAmount = $request->payment_method === 'cash'
                ? $request->paid_amount
                : $totalPrice;

            if ($request->payment_method === 'cash' && $paidAmount < $totalPrice) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Uang yang dibayarkan kurang dari total tagihan.',
                ], 422);
            }

            $changeAmount = $request->payment_method === 'cash'
                ? max(0, $paidAmount - $totalPrice)
                : 0;

            // Buat transaksi utama
            $transaction = Transaction::create([
                'user_id'        => $request->user()->id,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'total_price'    => $totalPrice,
                'status'         => 'paid',
                'payment_method' => $request->payment_method,
                'paid_amount'    => $paidAmount,
                'change_amount'  => $changeAmount,
                'notes'          => $request->notes,
            ]);

            // Simpan detail
            foreach ($cartItems as $cartItem) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $cartItem['product']->id,
                    'product_name'   => $cartItem['product']->name,
                    'price'          => $cartItem['product']->price,
                    'qty'            => $cartItem['qty'],
                    'subtotal'       => $cartItem['subtotal'],
                ]);
            }

            $transaction->load('details');

            return response()->json([
                'status'  => true,
                'message' => 'Transaksi berhasil! Invoice: ' . $transaction->invoice_number,
                'data'    => new TransactionResource($transaction),
            ], 201);
        });
    }
}
