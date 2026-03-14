<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\JsonResponse;

class PromoController extends Controller
{
    /**
     * Validasi kode promo aktif
     */
    public function validateCode(string $code): JsonResponse
    {
        $promo = Promo::where('code', $code)->where('is_active', true)->first();

        if (!$promo) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode promo tidak valid atau sudah tidak aktif.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Kode promo valid!',
            'data'    => [
                'code'  => $promo->code,
                'type'  => $promo->type,  // percentage | fixed
                'value' => $promo->value,
            ],
        ]);
    }
}
