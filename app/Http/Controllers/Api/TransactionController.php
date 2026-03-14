<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Riwayat transaksi milik kasir yang login
     */
    public function index(Request $request): JsonResponse
    {
        $transactions = Transaction::with('details')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'status'  => true,
            'message' => 'Riwayat transaksi',
            'data'    => TransactionResource::collection($transactions),
            'meta'    => [
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'total'        => $transactions->total(),
            ],
        ]);
    }

    /**
     * Detail satu transaksi + items
     */
    public function show(Transaction $transaction, Request $request): JsonResponse
    {
        // Kasir hanya bisa lihat transaksi miliknya
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        $transaction->load('details');

        return response()->json([
            'status'  => true,
            'message' => 'Detail transaksi',
            'data'    => new TransactionResource($transaction),
        ]);
    }
}
